<?php

namespace App\Services\Api;

use App\Enums\RedisEnum;
use App\Enums\ShipmentStatusEnum;
use App\Http\Resources\ShipmentResource;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\ShipmentAddress;
use App\Models\ShipmentStatus;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShipmentService extends BaseService
{
    public function __construct(private Shipment $shipment,
        private Payment $payment,
        private Item $item,
        private ShipmentStatus $shipmentStatus,
        private ShipmentAddress $shipmentAddress)
    {
    }

    public function createShipment(array $data): array
    {
        try {
            DB::beginTransaction();

            if (! $payment = $this->payment->where('id', $data['payment_id'])->first()) {
                return $this->error('Payment not found', [], 404);
            }

            if ($payment->status != 'success') {
                return $this->error('Payment is not successful', [], 422);
            }

            $trackingNumber = self::generateTrackingNumber();
            $data['tracking_number'] = $trackingNumber;

            $shipment = $this->shipment->create($data);

            $items = collect($data['items'])->map(function ($item) use ($data, $shipment) {
                $ratePerKg = config('services.shipment.rate_per_kg');
                $now = now();
                $item['created_at'] = $now;
                $item['updated_at'] = $now;
                $item['user_id'] = $data['user_id'];
                $item['shipment_id'] = $shipment->id;
                $item['rate_per_weight'] = $ratePerKg;
                $item['amount'] = ($item['weight'] * $item['rate_per_weight']) * $item['quantity'];

                return $item;
            })->toArray();

            $this->item->insert($items);

            $addresses = collect($data['addresses'])->map(function ($address) use ($data, $shipment) {
                $now = now();
                $address['created_at'] = $now;
                $address['updated_at'] = $now;
                $address['user_id'] = $data['user_id'];
                $address['shipment_id'] = $shipment->id;

                return $address;
            })->toArray();

            $this->shipmentAddress->insert($addresses);

            $this->shipmentStatus->create([
                'shipment_id' => $shipment->id,
                'status' => ShipmentStatusEnum::PENDING->value,
            ]);

            // Save tracking number in redis
            $this->storeShipmentDataInMemory($shipment->id);

            DB::commit();

            return $this->success('Shipment created', $shipment);

        } catch (\Throwable $th) {
            DB::rollBack();
            Redis::del(RedisEnum::TRACKING_NUMBER->value.$trackingNumber);
            throw $th;
            Log::error('An error occured while trying to create a payment '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }

    public function updateShipmentStatus(array $data, $shipmentId): array
    {
        try {
            if (! $shipment = $this->shipment->where('id', $shipmentId)->first()) {
                return $this->error('Shipment not found', [], 404);
            }

            $this->shipmentStatus->firstOrCreate([
                'shipment_id' => $shipmentId,
                'status' => ShipmentStatusEnum::fromName($data['status'])->value,
            ]);

            $this->storeShipmentDataInMemory($shipmentId);

            return $this->success('Shipment status updated', $shipment);
        } catch (\Throwable $th) {
            Log::error('An error occured while trying to update a shipment status '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }

    public function storeShipmentDataInMemory(string|int $shipmentId): Shipment
    {
        try {
            $shipment = $this->shipment->select('id', 'tracking_number', 'warehouse_id', 'type')
                ->with(['items:id,shipment_id,name,description,amount,quantity,amount',
                    'shipmentStatuses:id,shipment_id,status,created_at',
                    'shipmentAddresses:id,shipment_id,first_name,last_name,address,country,state,phone_number,landmark,type'])
                ->where(function ($query) use ($shipmentId) {
                    $query->where('id', $shipmentId)
                        ->orWhere('tracking_number', $shipmentId);
                })
                ->first();

            if (! $shipment) {
                throw new NotFoundHttpException('Shipment not found');
            }

            // * 432,000 = 5days, but then, we can make this expire after the item has gotten to the customer or when customer accpets the good we then remove from redis (business to detrmin the estimated delivery date)
            // todo: ask business questions in relations to delivery time

            Redis::setex(RedisEnum::TRACKING_NUMBER->value.$shipment->tracking_number, 432000, json_encode(new ShipmentResource($shipment)));

            return $shipment;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getShipmentByTrackingNumber(string $trackingNumber): array
    {
        $shipmentFromMemory = Redis::get(RedisEnum::TRACKING_NUMBER->value.$trackingNumber) ?? null;

        if ($shipmentFromMemory) {
            return $this->success('Success', json_decode($shipmentFromMemory));
        }

        $shipment = $this->storeShipmentDataInMemory($trackingNumber);

        return $this->success('Succes', $shipment);
    }

    public function getShipments(array $data): array
    {
        $shipments = $this->shipment->query()
            ->select('id', 'tracking_number', 'warehouse_id', 'type', 'created_at')
            ->with(['items:id,shipment_id,name,description,amount,quantity,amount',
                'shipmentStatuses:id,shipment_id,status,created_at',
                'shipmentAddresses:id,shipment_id,first_name,last_name,address,country,state,phone_number,landmark,type',
            ])->latest();

        $shipments->when(isset($data['warehouse_id']), function ($query) use ($data) {
            $query->where('warehouse_id', $data['warehouse_id']);
        });

        $shipments->when(isset($data['type']), function ($query) use ($data) {
            $query->where('type', $data['type']);
        });

        $shipments->when(isset($data['status']), function ($query) use ($data) {
            $query->whereHas('shipmentStatuses', function ($statusQuery) use ($data) {
                $statusQuery->where('status', ShipmentStatusEnum::fromName($data['status'])->value);
            });
        });

        return $this->success('Succes', $shipments->simplePaginate());
    }

    public static function generateTrackingNumber($length = 10): string
    {
        $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'; // removed O and 0 since it can be most times dificult for user to know if its an O or 0(zero)
        $trackingNumber = '';

        $maxIndex = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $trackingNumber .= $characters[rand(0, $maxIndex)];
        }

        // Check if it exist in redis
        $isTrackingNumberInRedis = Redis::exists(RedisEnum::TRACKING_NUMBER->value.$trackingNumber);
        if ($isTrackingNumberInRedis) {
            return self::generateTrackingNumber();
        }

        return $trackingNumber;
    }
}
