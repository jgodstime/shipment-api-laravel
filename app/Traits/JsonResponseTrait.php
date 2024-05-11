<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait JsonResponseTrait
{
    public function successResponse(string $message = '', mixed $data = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * @param  int  $errorCode
     */
    public function errorResponse(string $message = '', int $statusCode = 401): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }

    public function resolveResponse($data): JsonResponse|AnonymousResourceCollection
    {
        $statusCode = $data['status_code'];

        unset($data['status_code']);

        if ($data['data'] instanceof AnonymousResourceCollection) {
            return $data['data'];
        }

        return response()->json($data, $statusCode);
    }
}
