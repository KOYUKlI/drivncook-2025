<?php

use App\Http\Controllers\BO\DashboardController as BODashboardController;
use App\Http\Controllers\BO\FranchiseeController;
use App\Http\Controllers\BO\PurchaseOrderController;
use App\Http\Controllers\BO\ReportController as BOReportController;
use App\Http\Controllers\BO\StockItemController;
use App\Http\Controllers\BO\TruckController;
use App\Http\Controllers\BO\WarehouseController;
use App\Http\Controllers\FO\DashboardController as FODashboardController;
use App\Http\Controllers\FO\ReportController;
use App\Http\Controllers\FO\SaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\FranchiseApplicationController;
use App\Http\Controllers\Public\FranchisePageController;
use App\Http\Controllers\Public\HomeController;
use App\Services\PdfService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Http\Controllers\WebhookController;

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

Route::get('/reports/demo', function (PdfService $pdf) {
    $path = 'reports/demo-'.now()->format('Ym').'.pdf';
    $pdf->monthlySales([
        'franchisee' => ['name' => 'Franchise Paris'],
        'month' => now()->month, 'year' => now()->year,
        'total' => 50.00,
        'lines' => [
            ['date' => now()->toDateString(), 'item' => 'Burger', 'qty' => 10, 'price' => 5.00, 'amount' => 50.00],
        ],
    ], $path);

    return response()->download(Storage::disk('public')->path($path));
})->middleware('auth');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/devenir-franchise', [FranchisePageController::class, 'show'])->name('public.franchise');

// Public application routes (unified /applications)
Route::get('/applications/create', [FranchiseApplicationController::class, 'create'])->name('public.applications.create');
Route::post('/applications/draft', [FranchiseApplicationController::class, 'draft'])->name('public.applications.draft');
Route::post('/applications', [FranchiseApplicationController::class, 'store'])->name('public.applications.store');
Route::get('/applications/{id}', [FranchiseApplicationController::class, 'show'])->name('public.applications.show');
// Legacy aliases redirect permanently
Route::redirect('/application/create', '/applications/create', 301);
Route::get('/application/{id}', fn ($id) => redirect()->route('public.applications.show', $id));

// Dashboard redirect route
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = \Illuminate\Support\Facades\Auth::user();

    if ($user->hasRole(['admin', 'warehouse', 'fleet', 'tech'])) {
        return redirect()->route('bo.dashboard');
    }

    if ($user->hasRole('franchisee')) {
        return redirect()->route('fo.dashboard');
    }

    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Back Office routes (admin, warehouse, fleet, tech)
    Route::middleware('role:admin|warehouse|fleet|tech')->prefix('bo')->name('bo.')->group(function () {
        Route::get('/dashboard', [BODashboardController::class, 'index'])->name('dashboard');

        // Franchisees management (admin only)
        Route::middleware('role:admin')->group(function () {
            Route::resource('franchisees', FranchiseeController::class);
            Route::get('applications', [App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('applications.index');
            Route::get('applications/{application}', [App\Http\Controllers\Admin\ApplicationController::class, 'show'])->name('applications.show');
            Route::post('applications/{application}/status', [App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])->name('applications.update-status');
            Route::get('applications/files/{document}/download', [App\Http\Controllers\Admin\ApplicationController::class, 'downloadDocument'])->name('applications.files.download');
        });

        // Trucks management (admin, fleet)
        Route::middleware('role:admin|fleet')->group(function () {
            Route::resource('trucks', TruckController::class)->only(['index', 'show']);
            // Mission C actions
            Route::post('trucks/{truck}/deploy', [TruckController::class, 'openDeployment'])->name('trucks.deploy');
            Route::post('trucks/{truck}/maintenance/open', [TruckController::class, 'openMaintenance'])->name('trucks.maintenance.open');
            Route::post('maintenance/{log}/close', [TruckController::class, 'closeMaintenance'])->name('maintenance.close');
            Route::post('trucks/{truck}/schedule-deployment', [TruckController::class, 'scheduleDeployment'])->name('trucks.schedule-deployment');
            Route::post('trucks/{truck}/deployments/{deploymentId}/open', [TruckController::class, 'openDeployment'])->name('trucks.open-deployment');
            Route::post('trucks/{truck}/deployments/{deploymentId}/close', [TruckController::class, 'closeDeployment'])->name('trucks.close-deployment');
            Route::post('trucks/{truck}/schedule-maintenance', [TruckController::class, 'scheduleMaintenance'])->name('trucks.schedule-maintenance');
            Route::post('trucks/{truck}/maintenance/{maintenanceId}/open', [TruckController::class, 'openMaintenance'])->name('trucks.open-maintenance');
            Route::post('trucks/{truck}/maintenance/{maintenanceId}/close', [TruckController::class, 'closeMaintenance'])->name('trucks.close-maintenance');
            Route::patch('trucks/{truck}/status', [TruckController::class, 'updateStatus'])->name('trucks.update-status');
            Route::get('trucks/reports/utilization', [TruckController::class, 'utilizationReport'])->name('trucks.utilization-report');
        });

        // Purchase orders (admin, warehouse)
        Route::middleware('role:admin|warehouse')->group(function () {
            Route::get('reports/monthly', [BOReportController::class, 'monthly'])->name('reports.monthly');
            Route::post('reports/monthly/generate', [BOReportController::class, 'generate'])->name('reports.monthly.generate');
            Route::get('reports/{id}/download', [BOReportController::class, 'download'])->name('reports.download');

            Route::resource('warehouses', WarehouseController::class)->except(['show']);
            Route::resource('stock-items', StockItemController::class)->except(['show']);

            Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index', 'show', 'store', 'create']);
            Route::post('purchase-orders/{id}/validate-compliance', [PurchaseOrderController::class, 'validateCompliance'])->name('purchase-orders.validate-compliance');
            Route::post('purchase-orders/{id}/update-ratio', [PurchaseOrderController::class, 'updateRatio'])->name('purchase-orders.update-ratio');
            Route::post('purchase-orders/{id}/recalculate', [PurchaseOrderController::class, 'recalculate'])->name('purchase-orders.recalculate');
            Route::post('purchase-orders/{id}/status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.update-status');
            Route::get('purchase-orders/reports/compliance', [PurchaseOrderController::class, 'complianceReport'])->name('purchase-orders.compliance-report');
        });
    });

    // Front Office routes (franchisees)
    Route::middleware('role:franchisee')->prefix('fo')->name('fo.')->group(function () {
        Route::get('/dashboard', [FODashboardController::class, 'index'])->name('dashboard');
        Route::resource('sales', SaleController::class)->only(['index', 'create', 'store']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/reports/{id}/download', [ReportController::class, 'download'])->name('reports.download');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Routes de test temporaires - à supprimer après vérification
Route::get('/test-admin', function () {
    $user = App\Models\User::where('email', 'admin@local.test')->first();
    \Illuminate\Support\Facades\Auth::login($user);

    return redirect()->route('bo.dashboard');
})->name('test.admin');

Route::get('/test-franchisee', function () {
    $user = App\Models\User::where('email', 'fr@local.test')->first();
    \Illuminate\Support\Facades\Auth::login($user);

    return redirect()->route('fo.dashboard');
})->name('test.franchisee');

// Mail preview routes (local only)
if (app()->environment('local')) {
    Route::prefix('dev/mail')->name('dev.mail.')->middleware('auth')->group(function () {
        Route::get('preview', [App\Http\Controllers\Dev\MailPreviewController::class, 'index'])->name('index');
        Route::get('preview/{mailable}', [App\Http\Controllers\Dev\MailPreviewController::class, 'preview'])->name('preview');
    });
}
