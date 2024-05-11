<?php

namespace App\Services\Api;

use App\Enums\UserRoleEnum;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService extends BaseService
{
    public function __construct(private User $user)
    {
    }

    public function createUser(array $data): array
    {
        try {
            $data['password'] = bcrypt($data['password']);
            $data['role'] = UserRoleEnum::USER->value;

            $user = $this->user->create($data);

            return $this->success('User created', $user);
        } catch (\Throwable $th) {
            Log::error('An error occured while trying to create a user '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }

    public function updateUser(array $data, int $userId): array
    {
        try {

            if (! $user = $this->user->where('id', $userId)->first()) {
                return $this->error('User not found', [], 404);
            }

            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $user = $user->update($data);

            return $this->success('User updated', $user);

        } catch (\Throwable $th) {
            Log::error('An error occured while trying to update a user '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }

    public function deleteUser(int $userId): array
    {
        try {

            if (! $user = $this->user->where('id', $userId)->first()) {
                return $this->error('User not found', [], 404);
            }

            $user = $user->delete();

            return $this->success('User deleted', $user);

        } catch (\Throwable $th) {
            Log::error('An error occured while trying to delete a user '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }

    public function getUser(int $userId): array
    {
        try {

            if (! $user = $this->user->where('id', $userId)->first()) {
                throw new NotFoundHttpException('User not found');
            }

            return $this->success('Success', $user);

        } catch (\Throwable $th) {
            Log::error('An error occured while trying to get a user '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }
}
