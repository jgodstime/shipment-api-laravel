<?php

namespace App\Traits;

trait ArrayResponseTrait
{
    public function success(string $message = 'Success', mixed $data = []): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status_code' => 200,
        ];
    }

    /**
     * @param  int  $statusCode
     */
    public function error(string $message = 'Failed', mixed $data = [], $statusCode = 500): array
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => $data,
            'status_code' => $statusCode,
        ];
    }
}
