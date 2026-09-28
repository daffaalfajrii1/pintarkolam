<?php

use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\CycleController;
use App\Http\Controllers\Api\V1\MyPondController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\PublicController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
            Route::put('password', [AuthController::class, 'updatePassword']);
        });
    });

    Route::get('home', [PublicController::class, 'home']);
    Route::get('species', [PublicController::class, 'species']);
    Route::get('products', [PublicController::class, 'products']);
    Route::get('products/{product}', [PublicController::class, 'product']);
    Route::get('farmers', [PublicController::class, 'farmers']);
    Route::get('map/ponds', [PublicController::class, 'mapPonds']);
    Route::get('blog', [PublicController::class, 'blog']);
    Route::get('blog/{post:slug}', [PublicController::class, 'blogShow']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('my/dashboard', [MyPondController::class, 'dashboard']);
        Route::get('my/profile', [MyPondController::class, 'profile']);
        Route::put('my/profile', [MyPondController::class, 'updateProfile']);
        Route::get('my/ponds', [MyPondController::class, 'index']);
        Route::post('my/ponds', [MyPondController::class, 'store']);
        Route::get('my/ponds/{pond}', [MyPondController::class, 'show']);
        Route::put('my/ponds/{pond}', [MyPondController::class, 'update']);
        Route::delete('my/ponds/{pond}', [MyPondController::class, 'destroy']);

        Route::get('cycles', [CycleController::class, 'index']);
        Route::post('cycles', [CycleController::class, 'store']);
        Route::get('cycles/{cycle}', [CycleController::class, 'show']);
        Route::put('cycles/{cycle}', [CycleController::class, 'update']);
        Route::get('cycles/{cycle}/water-quality', [CycleController::class, 'waterQualityIndex']);
        Route::post('cycles/{cycle}/water-quality', [CycleController::class, 'waterQualityStore']);
        Route::get('cycles/{cycle}/recommendations', [CycleController::class, 'recommendations']);
        Route::get('cycles/{cycle}/feeding-schedules', [CycleController::class, 'feedingSchedules']);
        Route::post('cycles/{cycle}/feeding-schedules', [CycleController::class, 'feedingSchedulesStore']);
        Route::get('cycles/{cycle}/feeding-logs', [CycleController::class, 'feedingLogsIndex']);
        Route::post('cycles/{cycle}/feeding-logs', [CycleController::class, 'feedingLogsStore']);
        Route::get('cycles/{cycle}/growth-records', [CycleController::class, 'growthRecords']);
        Route::post('cycles/{cycle}/growth-records', [CycleController::class, 'growthRecordsStore']);
        Route::get('cycles/{cycle}/harvest-estimate', [CycleController::class, 'harvestEstimate']);
        Route::get('cycles/{cycle}/report', [CycleController::class, 'report']);
        Route::get('cycles/{cycle}/mortality', [CycleController::class, 'mortalityIndex']);
        Route::post('cycles/{cycle}/mortality', [CycleController::class, 'mortalityStore']);
        Route::get('cycles/{cycle}/costs', [CycleController::class, 'costsIndex']);
        Route::post('cycles/{cycle}/costs', [CycleController::class, 'costsStore']);
        Route::delete('cycles/{cycle}/costs/{entry}', [CycleController::class, 'costsDestroy']);
        Route::get('my/notes', [CycleController::class, 'notes']);

        Route::get('my/products', [ProductController::class, 'mine']);
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'destroy']);

        Route::post('notifications/device-token', [NotificationController::class, 'storeDeviceToken']);
        Route::delete('notifications/device-token', [NotificationController::class, 'destroyDeviceToken']);
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::put('notifications/preferences', [NotificationController::class, 'updatePreferences']);

        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::get('dashboard', [AdminController::class, 'dashboard']);
            Route::get('users', [AdminController::class, 'users']);
            Route::get('farmers/pending', [AdminController::class, 'pendingFarmers']);
            Route::put('farmers/{farmer}', [AdminController::class, 'verifyFarmer']);
            Route::put('products/{product}/moderate', [AdminController::class, 'moderateProduct']);
            Route::get('reports', [AdminController::class, 'reports']);
        });
    });
});
