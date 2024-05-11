<?php

namespace App\Services\Api;

use App\Models\Payment;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentService extends BaseService
{
    public function __construct(private Payment $payment)
    {
    }

    public function createPayment(array $data): array
    {
        try {
            if ($payment = $this->payment->where('reference', $data['reference'])->first()) {
                return $this->error('Payment reference already exist', [], 442);
            }

            $payment = $this->payment->create($data);

            return $this->success('Payment created', $payment);

        } catch (\Throwable $th) {
            Log::error('An error occured while trying to create a payment '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }

    public function updatePayment(array $data, int $paymentId): array
    {
        try {

            if (! $payment = $this->payment->where('id', $paymentId)->first()) {
                throw new NotFoundHttpException('Payment not found');
            }

            $payment->update($data);

            return $this->success('Payment updated', $payment);

        } catch (\Throwable $th) {
            Log::error('An error occured while trying to update a payment '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }

    public function getPayment(int $paymentId): array
    {
        try {

            if (! $payment = $this->payment->where('id', $paymentId)->first()) {
                throw new NotFoundHttpException('Payment not found');
            }

            return $this->success('Success', $payment);

        } catch (\Throwable $th) {
            Log::error('An error occured while trying to get a user '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }
}
