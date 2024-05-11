<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Api\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function __construct(private UserService $userService)
    {
    }

    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $response = $this->userService->createUser($request->validated());

        return $this->resolveResponse($response);
    }

    public function updateUser(int $userId, UpdateUserRequest $request): JsonResponse
    {
        $response = $this->userService->updateUser($request->validated(), $userId);

        return $this->resolveResponse($response);
    }

    public function deleteUser(int $userId): JsonResponse
    {
        $response = $this->userService->deleteUser($userId);

        return $this->resolveResponse($response);
    }

    public function getUser(int $userId): JsonResponse
    {
        $response = $this->userService->getUser($userId);

        $response['data'] = new UserResource($response['data']);

        return $this->resolveResponse($response);
    }
}
