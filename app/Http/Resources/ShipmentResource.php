<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tracking_number' => $this->tracking_number,
            'type' => $this->type,
            'warehouse' => new WarehouseResource($this->whenLoaded('warehouse')),
            'items' => ItemResource::collection($this->whenLoaded('items')),
            'shipment_statuses' => ShipmentStatusResource::collection($this->whenLoaded('shipmentStatuses')),
            'shipment_addresses' => ShipmentAddressResource::collection($this->whenLoaded('shipmentAddresses')),
        ];
    }
}
