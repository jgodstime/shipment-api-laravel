<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1/')->group(function () {

    Route::post('/auth/login', [\App\Http\Controllers\Api\V1\AuthController::class, 'login']);

    // only admin auth user can access this routes
    Route::middleware(['auth:sanctum', IsAdmin::class])->group(function () {

        Route::get('/warehouses', [\App\Http\Controllers\Api\V1\WarehouseController::class, 'getWarehoueses']);

        Route::prefix('users')->group(function () {
            Route::post('/', [\App\Http\Controllers\Api\V1\UserController::class, 'createUser']);
            Route::get('/{userId}', [\App\Http\Controllers\Api\V1\UserController::class, 'getUser']);
            Route::delete('/{userId}', [\App\Http\Controllers\Api\V1\UserController::class, 'deleteUser']);
            Route::patch('/{userId}', [\App\Http\Controllers\Api\V1\UserController::class, 'updateUser']);
        });

        Route::prefix('payments')->group(function () {
            Route::post('/', [\App\Http\Controllers\Api\V1\PaymentController::class, 'createPayment']);
            Route::patch('/{userId}', [\App\Http\Controllers\Api\V1\PaymentController::class, 'updatePayment']);
            Route::get('/{userId}', [\App\Http\Controllers\Api\V1\PaymentController::class, 'getPayment']);
        });

        Route::prefix('shipments')->group(function () {
            Route::post('/', [\App\Http\Controllers\Api\V1\ShipmentController::class, 'createShipment']);
            Route::patch('/{shipmentId}', [\App\Http\Controllers\Api\V1\ShipmentController::class, 'updateShipmentStatus']);
            Route::get('/{trackingNumber}/track', [\App\Http\Controllers\Api\V1\ShipmentController::class, 'getShipmentByTrackingNumber']);
            Route::get('/', [\App\Http\Controllers\Api\V1\ShipmentController::class, 'getShipments']);
        });
    });
});
