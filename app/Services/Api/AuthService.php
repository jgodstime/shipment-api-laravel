<?php

namespace App\Services\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService extends BaseService
{
    public function __construct(private User $user)
    {
    }

    public function login(array $data): array
    {
        try {

            $user = $this->user->where('email', $data['email'])->first();

            if (! $user) {
                return $this->error('Invalid login credential', [], 401);
            }

            if (! Hash::check($data['password'], $user->password)) {
                return $this->error('Invalid login credential', [], 401);
            }

            $user->token = $user->createToken($user->email)->plainTextToken;

            return $this->success('Your are logged in', new UserResource($user));

        } catch (\Throwable $th) {
            Log::error('An error occured while trying to login '.$th->getMessage());

            return $this->error('An error occured, kindly try again later');
        }
    }
}
