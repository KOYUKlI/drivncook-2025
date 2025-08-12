<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{InventoryController as AdminInventoryController, InventoryLotController as AdminInventoryLotController, StockOrderController as AdminStockOrderController};
use App\Http\Controllers\Franchise\{StockOrderController, StockOrderItemController};
use App\Http\Controllers\Admin\{CommissionController,ComplianceController,PaymentController as AdminPaymentController, SupplierController as AdminSupplierController, SupplyController as AdminSupplyController, TruckController as AdminTruckController, TruckDeploymentController as AdminTruckDeploymentController, WarehouseController as AdminWarehouseController, LocationController as AdminLocationController};

Route::middleware('auth:sanctum')->group(function () {
    // Admin scoped
    Route::prefix('admin')->middleware('admin')->group(function(){
        Route::apiResource('supplies', AdminSupplyController::class)->only(['index','store','show','update','destroy']);
        Route::apiResource('suppliers', AdminSupplierController::class)->only(['index','store','show','update','destroy']);
        Route::apiResource('warehouses', AdminWarehouseController::class)->only(['index','store','show','update','destroy']);
        Route::apiResource('trucks', AdminTruckController::class)->only(['index','store','show','update','destroy']);
        Route::apiResource('deployments', AdminTruckDeploymentController::class)->only(['index','store','show','update','destroy']);
        Route::apiResource('locations', AdminLocationController::class)->only(['index','store','show','update','destroy']);
        Route::apiResource('stock-orders', AdminStockOrderController::class)->only(['index','show','update','destroy']);
        Route::get('inventory', [AdminInventoryController::class,'index']);
        Route::get('inventory/{inventory}', [AdminInventoryController::class,'show']);
        Route::post('inventory/{inventory}/adjust',[AdminInventoryController::class,'adjust']);
        Route::post('inventory/{inventory}/move',[AdminInventoryController::class,'move']);
        Route::post('inventory/{inventory}/lots',[AdminInventoryLotController::class,'store']);
        Route::put('inventory/{inventory}/lots/{lot}',[AdminInventoryLotController::class,'update']);
        Route::delete('inventory/{inventory}/lots/{lot}',[AdminInventoryLotController::class,'destroy']);
        Route::get('commissions',[CommissionController::class,'index']);
        Route::get('compliance',[ComplianceController::class,'index']);
        Route::get('payments',[AdminPaymentController::class,'index']);
    });

    // Franchise scoped
    Route::prefix('franchise')->middleware('franchise')->group(function(){
        Route::apiResource('stock-orders', StockOrderController::class)->only(['index','store','show','update','destroy']);
        Route::post('stock-orders/{stockorder}/items',[StockOrderItemController::class,'store']);
    });
});
