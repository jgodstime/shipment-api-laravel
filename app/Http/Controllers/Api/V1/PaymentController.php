<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Services\Api\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends BaseController
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    public function createPayment(CreatePaymentRequest $request): JsonResponse
    {
        $response = $this->paymentService->createPayment($request->validated());

        return $this->resolveResponse($response);
    }

    public function updatePayment(int $paymentId, UpdatePaymentRequest $request): JsonResponse
    {
        $response = $this->paymentService->updatePayment($request->validated(), $paymentId);

        return $this->resolveResponse($response);
    }

    public function getPayment(int $paymentId): JsonResponse
    {
        $response = $this->paymentService->getPayment($paymentId);

        return $this->resolveResponse($response);
    }
}
