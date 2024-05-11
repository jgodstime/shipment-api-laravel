<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\RedisEnum;
use App\Http\Controllers\BaseController;
use App\Traits\ArrayResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class WarehouseController extends BaseController
{
    use ArrayResponseTrait;

    public function getWarehoueses(): JsonResponse
    {
        $data = Redis::get(RedisEnum::WAREHOUSES->value) ?? null;

        $response = $this->success('Success', $data ? json_decode($data) : []);

        return $this->resolveResponse($response);
    }
}
