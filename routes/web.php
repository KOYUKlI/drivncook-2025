<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{ DashboardController as AdminDashboardController,
    TruckController as AdminTruckController,
    WarehouseController as AdminWarehouseController,
    FranchiseeController as AdminFranchiseeController,
    SalesController as AdminSalesController,
    SupplyController as AdminSupplyController,
    SupplierController as AdminSupplierController,
    CommissionController as AdminCommissionController };
use App\Http\Controllers\Admin\ComplianceController as AdminComplianceController;
use App\Http\Controllers\Admin\{ LocationController as AdminLocationController, TruckDeploymentController as AdminTruckDeploymentController };
use App\Http\Controllers\Franchise\{ DashboardController as FranchiseDashboardController,
    TruckController as FranchiseTruckController,
    StockOrderController as FranchiseStockOrderController };
use App\Http\Controllers\Admin\{ NewsletterController as AdminNewsletterController, LoyaltyRuleController as AdminLoyaltyRuleController, PaymentController as AdminPaymentController };
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FranchiseApplicationController;
use App\Http\Controllers\Admin\FranchiseApplicationController as AdminFranchiseApplicationController;

Route::get('/', function () {
    return view('welcome');
});

// Public franchise application
Route::get('/franchise/apply', [FranchiseApplicationController::class, 'create'])->name('franchise.apply');
Route::post('/franchise/apply', [FranchiseApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('franchise.apply.post');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    // Franchise users: if not attached yet, go to profile instead of loop
    if ($user->role === 'franchise' && empty($user->franchise_id)) {
        return redirect()->route('profile.edit')->with('error', "Your account isn't linked to any franchisee yet.");
    }
    return redirect()->route('franchise.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes accessibles seulement après authentication
Route::middleware('auth')->group(function () {
    Route::middleware('admin')->prefix('admin')->as('admin.')->scopeBindings()->group(function() {
        // Dashboard (admin home)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // CRUD resources for admin
        Route::resource('trucks', AdminTruckController::class);
        Route::resource('warehouses', AdminWarehouseController::class);
    Route::resource('franchisees', AdminFranchiseeController::class);
    // Manage user attachments to franchisees
    Route::post('franchisees/{franchisee}/users/attach', [AdminFranchiseeController::class, 'attachUser'])->name('franchisees.users.attach');
    Route::delete('franchisees/{franchisee}/users/{user}', [AdminFranchiseeController::class, 'detachUser'])->name('franchisees.users.detach');
    Route::resource('locations', AdminLocationController::class);
    Route::resource('deployments', AdminTruckDeploymentController::class);
    Route::resource('supplies', AdminSupplyController::class);
    // Inventory explorer + actions
    Route::resource('inventory', \App\Http\Controllers\Admin\InventoryController::class)->only(['index','show']);
    Route::post('inventory/adjust', [\App\Http\Controllers\Admin\InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('inventory/move', [\App\Http\Controllers\Admin\InventoryController::class, 'move'])->name('inventory.move');
    Route::resource('inventory.lots', \App\Http\Controllers\Admin\InventoryLotController::class)->except(['index','show']);
    // Dishes CRUD + Ingredients (BOM)
    Route::resource('dishes', \App\Http\Controllers\Admin\DishController::class);
    Route::post('dishes/{dish}/ingredients', [\App\Http\Controllers\Admin\DishIngredientController::class, 'store'])->name('dishes.ingredients.store');
    Route::delete('dishes/{dish}/ingredients/{ingredient}', [\App\Http\Controllers\Admin\DishIngredientController::class, 'destroy'])->name('dishes.ingredients.destroy');
    Route::resource('suppliers', AdminSupplierController::class);
    Route::resource('commissions', AdminCommissionController::class)->only(['index','show','update']);
    Route::resource('loyalty-rules', AdminLoyaltyRuleController::class);
    Route::resource('newsletters', AdminNewsletterController::class);
    Route::post('newsletters/{newsletter}/send', [AdminNewsletterController::class,'send'])->name('newsletters.send');
    Route::get('payments', [AdminPaymentController::class,'index'])->name('payments.index');
    Route::post('sales/{order}/payments', [AdminPaymentController::class,'store'])->name('sales.payments.store');
    Route::get('payments/{payment}', [AdminPaymentController::class,'show'])->name('payments.show');
    Route::post('payments/{payment}/capture', [AdminPaymentController::class,'capture'])->name('payments.capture');
    Route::post('payments/{payment}/refund', [AdminPaymentController::class,'refund'])->name('payments.refund');
    // Compliance 80/20
    Route::get('compliance', [AdminComplianceController::class, 'index'])->name('compliance.index');
    Route::get('compliance/{franchisee}/edit', [AdminComplianceController::class, 'edit'])->name('compliance.edit');
    Route::put('compliance/{franchisee}', [AdminComplianceController::class, 'update'])->name('compliance.update');
        // Sales: only index and show (admin can view sales but not create)
        Route::resource('sales', AdminSalesController::class)->only(['index', 'show']);
    // Exports
    Route::get('exports/sales.pdf', [\App\Http\Controllers\Admin\ExportController::class, 'salesPdf'])->name('exports.sales.pdf');

    // Franchise applications review
    Route::get('franchise-applications', [AdminFranchiseApplicationController::class,'index'])->name('franchise-applications.index');
    Route::get('franchise-applications/{id}', [AdminFranchiseApplicationController::class,'show'])->name('franchise-applications.show');
    Route::post('franchise-applications/{id}/approve', [AdminFranchiseApplicationController::class,'approve'])->name('franchise-applications.approve');
    Route::post('franchise-applications/{id}/reject', [AdminFranchiseApplicationController::class,'reject'])->name('franchise-applications.reject');

    // Create brand-new user inside a franchise
    Route::get('franchises/{franchise}/users/create', [\App\Http\Controllers\Admin\FranchiseUserController::class, 'create'])->name('franchises.users.create');
    Route::post('franchises/{franchise}/users', [\App\Http\Controllers\Admin\FranchiseUserController::class, 'store'])->name('franchises.users.store');
    });
    // Groupe de routes Franchise (prefix 'franchise/*', name prefix 'franchise.')
    Route::middleware(['franchise','franchise.attached'])->prefix('franchise')->as('franchise.')->scopeBindings()->group(function() {
        // Dashboard (franchise home)
        Route::get('/dashboard', [FranchiseDashboardController::class, 'index'])->name('dashboard');
        // CRUD resources for franchise
        Route::resource('trucks', FranchiseTruckController::class);
        Route::resource('stockorders', FranchiseStockOrderController::class);
        Route::post('stockorders/{stockorder}/complete', [FranchiseStockOrderController::class, 'complete'])
            ->name('stockorders.complete');
        // Nested: add/remove items to a stock order
        Route::post('stockorders/{stockorder}/items', [\App\Http\Controllers\Franchise\StockOrderItemController::class, 'store'])
            ->name('stockorders.items.store');
        Route::delete('stockorders/{stockorder}/items/{item}', [\App\Http\Controllers\Franchise\StockOrderItemController::class, 'destroy'])
            ->name('stockorders.items.destroy');
        // (Note: Warehouses are managed by admin; franchisee does not have direct warehouse routes)
    // Maintenance records
    Route::resource('maintenance', \App\Http\Controllers\Franchise\MaintenanceRecordController::class)->except(['show']);
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
