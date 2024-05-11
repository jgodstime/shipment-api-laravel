<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateShipmentRequest;
use App\Http\Requests\GetShipmentRequest;
use App\Http\Requests\UpdateShipmentStatusRequest;
use App\Http\Resources\ShipmentResource;
use App\Services\Api\ShipmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShipmentController extends BaseController
{
    public function __construct(private ShipmentService $shipmentService)
    {
    }

    public function createShipment(CreateShipmentRequest $request): JsonResponse
    {
        $response = $this->shipmentService->createShipment($request->validated());

        return $this->resolveResponse($response);
    }

    public function updateShipmentStatus(int $shipmentStatusId, UpdateShipmentStatusRequest $request): JsonResponse
    {
        $response = $this->shipmentService->updateShipmentStatus($request->validated(), $shipmentStatusId);

        return $this->resolveResponse($response);
    }

    public function getShipmentByTrackingNumber(string $trackingNumber): JsonResponse
    {
        $response = $this->shipmentService->getShipmentByTrackingNumber($trackingNumber);

        return $this->resolveResponse($response);
    }

    public function getShipments(GetShipmentRequest $request): JsonResponse|AnonymousResourceCollection
    {
        $response = $this->shipmentService->getShipments($request->validated());
        $response['data'] = ShipmentResource::collection($response['data']);

        return $this->resolveResponse($response);
    }
}
