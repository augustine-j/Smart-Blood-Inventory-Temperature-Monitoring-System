<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BloodBagController;
use App\Http\Controllers\Api\TemperatureLogController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InventoryAnalyticsController;
use App\Http\Controllers\Api\RelationshipDemoController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',[AuthController::class,'me']);
    Route::post('/logout',[AuthController::class, 'logout']);

    Route::middleware('role:admin')->get('/admin-check', function () {
        return response()->json([
            'message' => 'Admin access granted',
        ]);
    });

    Route::middleware('role:admin,staff')->get('/staff-check',function() {
        return response()->json([
            'message' => 'Staff/Admin access granted',
        ]);
    });

    Route::middleware('role:admin,monitoring')->get('/monitoring-check', function () {
        return response()->json([
            'message' => 'Monitoring/Admin access granted',
        ]);
    });

    Route::apiResource('blood-bags', BloodBagController::class)
    ->middleware('role:admin,staff');

    Route::post('/temperature-logs', [TemperatureLogController::class, 'store'])
    ->middleware('role:admin,monitoring');

    Route::get('/refrigerators/{refrigerator}/temperature-risk', [TemperatureLogController::class, 'refrigeratorRisk'])
    ->middleware('role:admin,monitoring');

    Route::get('/inventory/expiry-summary', [InventoryAnalyticsController::class, 'expirySummary'])
    ->middleware('role:admin,staff,monitoring');

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('role:admin,staff,monitoring');

    Route::prefix('relationship-demo')
    ->middleware('role:admin,staff,monitoring')
    ->group(function () {
        Route::get('/blood-banks', [RelationshipDemoController::class, 'bloodBanks']);
        Route::get('/critical-refrigerators', [RelationshipDemoController::class, 'refrigeratorsWithCriticalLogs']);
        Route::get('/available-stock-blood-banks', [RelationshipDemoController::class, 'bloodBanksWithAvailableStock']);
    });
});
