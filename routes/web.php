<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{ DashboardController as AdminDashboardController,
    TruckController as AdminTruckController,
    WarehouseController as AdminWarehouseController,
    FranchiseeController as AdminFranchiseeController,
    SalesController as AdminSalesController,
    SupplyController as AdminSupplyController };
use App\Http\Controllers\Franchise\{ DashboardController as FranchiseDashboardController,
    TruckController as FranchiseTruckController,
    StockOrderController as FranchiseStockOrderController };

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes accessibles seulement après authentication
Route::middleware('auth')->group(function () {
    Route::middleware('admin')->prefix('admin')->as('admin.')->group(function() {
        // Dashboard (admin home)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // CRUD resources for admin
        Route::resource('trucks', AdminTruckController::class);
        Route::resource('warehouses', AdminWarehouseController::class);
        Route::resource('franchisees', AdminFranchiseeController::class);
        Route::resource('supplies', AdminSupplyController::class);
        // Sales: only index and show (admin can view sales but not create)
        Route::resource('sales', AdminSalesController::class)->only(['index', 'show']);
    });
    // Groupe de routes Franchise (prefix 'franchise/*', name prefix 'franchise.')
    Route::middleware('franchise')->prefix('franchise')->as('franchise.')->group(function() {
        // Dashboard (franchise home)
        Route::get('/dashboard', [FranchiseDashboardController::class, 'index'])->name('dashboard');
        // CRUD resources for franchise
        Route::resource('trucks', FranchiseTruckController::class);
        Route::resource('stockorders', FranchiseStockOrderController::class);
        // (Note: Warehouses are managed by admin; franchisee does not have direct warehouse routes)
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
