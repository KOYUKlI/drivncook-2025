<?php

use App\Http\Controllers\BO\DashboardController as BODashboardController;
use App\Http\Controllers\BO\FranchiseeController;
use App\Http\Controllers\BO\Reports\ComplianceController as BOComplianceController;
use App\Http\Controllers\BO\ReportController as BOReportController;
use App\Http\Controllers\BO\StockItemController;
use App\Http\Controllers\BO\TruckController;
use App\Http\Controllers\BO\WarehouseController;
use App\Http\Controllers\BO\AuditLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\FranchiseApplicationController;
use App\Http\Controllers\Public\FranchisePageController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\BO\TruckDeploymentController;
use App\Services\PdfService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Http\Controllers\WebhookController;

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

// Locale switcher (web, csrf-protected)
Route::post('/locale', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['fr','en'])) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
    }
    return redirect($request->input('redirect', url()->previous()));
})->middleware('web')->name('locale.switch');

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
            // Audit logs viewer (admin only)
            Route::get('audit', [AuditLogController::class, 'index'])->name('audit.index');
            Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
            Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
            Route::post('applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.update-status');
            Route::get('applications/files/{document}/download', [ApplicationController::class, 'downloadDocument'])->name('applications.download-document');
            Route::post('applications/{application}/prequalify', [ApplicationController::class, 'prequalify'])->name('applications.prequalify');
            Route::post('applications/{application}/interview', [ApplicationController::class, 'interview'])->name('applications.interview');
            Route::post('applications/{application}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
            Route::post('applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
        });

    // Trucks management (admin, fleet)
    Route::middleware('role:admin|fleet')->group(function () {
            Route::get('trucks/create', [TruckController::class, 'create'])->name('trucks.create');
            Route::post('trucks', [TruckController::class, 'store'])->name('trucks.store');
            Route::get('trucks/{truck}/edit', [TruckController::class, 'edit'])->name('trucks.edit');
            Route::patch('trucks/{truck}', [TruckController::class, 'update'])->name('trucks.update');
            Route::resource('trucks', TruckController::class)->only(['index', 'show']);
            // Mission C actions
            Route::post('trucks/{truck}/deploy', [TruckController::class, 'openDeployment'])->name('trucks.deploy');
            // Maintenance (legacy simple open/close/download handled by dedicated controller)
            Route::post('trucks/{truck}/schedule-deployment', [TruckController::class, 'scheduleDeployment'])->name('trucks.schedule-deployment');
            Route::post('trucks/{truck}/deployments/{deploymentId}/open', [TruckController::class, 'openDeployment'])->name('trucks.open-deployment');
            Route::post('trucks/{truck}/deployments/{deploymentId}/close', [TruckController::class, 'closeDeployment'])->name('trucks.close-deployment');
            // Maintenance (PHASE C)
            Route::post('trucks/{truck}/maintenance/open', [App\Http\Controllers\BO\TruckMaintenanceController::class, 'open'])->name('trucks.maintenance.open');
            Route::post('maintenance/{log}/close', [App\Http\Controllers\BO\TruckMaintenanceController::class, 'close'])->name('maintenance.close');
            Route::get('maintenance/{log}/download', [App\Http\Controllers\BO\TruckMaintenanceController::class, 'download'])->name('maintenance.download');
            
            // Enhanced Maintenance System
            Route::get('maintenance', [App\Http\Controllers\MaintenanceLogController::class, 'index'])->name('maintenance.index');
            Route::get('maintenance/create', [App\Http\Controllers\MaintenanceLogController::class, 'create'])->name('maintenance.create');
            Route::post('maintenance', [App\Http\Controllers\MaintenanceLogController::class, 'store'])->name('maintenance.store');
            Route::get('maintenance/{maintenanceLog}', [App\Http\Controllers\MaintenanceLogController::class, 'show'])->name('maintenance.show');
            Route::get('maintenance/{maintenanceLog}/edit', [App\Http\Controllers\MaintenanceLogController::class, 'edit'])->name('maintenance.edit');
            Route::put('maintenance/{maintenanceLog}', [App\Http\Controllers\MaintenanceLogController::class, 'update'])->name('maintenance.update');
            Route::post('trucks/{truck}/maintenance/schedule', [App\Http\Controllers\MaintenanceLogController::class, 'store'])->name('maintenance.schedule');
            Route::post('maintenance/{maintenanceLog}/open', [App\Http\Controllers\MaintenanceLogController::class, 'open'])->name('maintenance.open');
            // Fallback GET to avoid 404 when users hit the URL directly; redirect to show
            Route::get('maintenance/{maintenanceLog}/open', function (App\Models\MaintenanceLog $maintenanceLog) {
                return redirect()->route('bo.maintenance.show', $maintenanceLog);
            })->name('maintenance.open.fallback');
            Route::post('maintenance/{maintenanceLog}/pause', [App\Http\Controllers\MaintenanceLogController::class, 'pause'])->name('maintenance.pause');
            Route::post('maintenance/{maintenanceLog}/resume', [App\Http\Controllers\MaintenanceLogController::class, 'resume'])->name('maintenance.resume');
            Route::post('maintenance/{maintenanceLog}/close', [App\Http\Controllers\MaintenanceLogController::class, 'close'])->name('maintenance.close.enhanced');
            Route::post('maintenance/{maintenanceLog}/cancel', [App\Http\Controllers\MaintenanceLogController::class, 'cancel'])->name('maintenance.cancel');
            Route::get('maintenance/attachment/{attachment}', [App\Http\Controllers\MaintenanceLogController::class, 'downloadAttachment'])->name('maintenance.attachment.download');
            Route::post('maintenance/{maintenanceLog}/attachment', [App\Http\Controllers\MaintenanceLogController::class, 'uploadAttachment'])->name('maintenance.attachment.upload');
            // Deployments (PHASE D)
            Route::post('trucks/{truck}/deployments/schedule', [TruckDeploymentController::class, 'schedule'])->name('deployments.schedule');
            Route::post('deployments/{deployment}/open', [TruckDeploymentController::class, 'open'])->name('deployments.open');
            Route::post('deployments/{deployment}/close', [TruckDeploymentController::class, 'close'])->name('deployments.close');
            Route::post('deployments/{deployment}/cancel', [TruckDeploymentController::class, 'cancel'])->name('deployments.cancel');
            Route::post('deployments/{deployment}/reschedule', [TruckDeploymentController::class, 'reschedule'])->name('deployments.reschedule');
            Route::get('deployments/export', [TruckDeploymentController::class, 'export'])->name('deployments.export');
            Route::patch('trucks/{truck}/status', [TruckController::class, 'updateStatus'])->name('trucks.update-status');
            Route::get('trucks/reports/utilization', [TruckController::class, 'utilizationReport'])->name('trucks.utilization-report');
            // Secure document download (BO-only)
            Route::get('trucks/{truck}/files/{type}', [TruckController::class, 'downloadDocument'])
                ->whereIn('type', ['registration','insurance'])
                ->name('trucks.files.download');
        });

        // Purchase orders (admin, warehouse)
        Route::middleware('role:admin|warehouse')->group(function () {
            Route::get('reports/monthly', [BOReportController::class, 'monthly'])->name('reports.monthly');
            Route::post('reports/monthly/generate', [BOReportController::class, 'generate'])->name('reports.monthly.generate');
            Route::get('reports/{id}/download', [BOReportController::class, 'download'])->name('reports.download');
            // 80/20 Compliance report (moved under reports)
            Route::get('reports/compliance', [BOComplianceController::class, 'index'])->name('reports.compliance');

            // Warehouses, Stock Items and Inventory
            Route::resource('warehouses', WarehouseController::class)->except(['show']);
            Route::get('warehouses/inventory', [App\Http\Controllers\BO\WarehouseInventoryController::class, 'index'])->name('warehouses.inventory');
            Route::get('warehouses/{warehouse}/inventory', [App\Http\Controllers\BO\WarehouseInventoryController::class, 'show'])->name('warehouses.inventory.show');
            Route::get('warehouses/{id}/dashboard', [App\Http\Controllers\BO\WarehouseDashboardController::class, 'show'])->name('warehouses.dashboard');
            Route::get('warehouses/{id}/dashboard/export', [App\Http\Controllers\BO\WarehouseDashboardController::class, 'exportMovements'])->name('warehouses.dashboard.export');
            Route::resource('stock-items', StockItemController::class)->except(['show']);
            Route::resource('stock-movements', App\Http\Controllers\BO\StockMovementController::class)->only(['create', 'store']);
            // Replenishments
            Route::get('replenishments', [App\Http\Controllers\BO\ReplenishmentController::class, 'index'])->name('replenishments.index');
            Route::get('replenishments/export', [App\Http\Controllers\BO\ReplenishmentController::class, 'export'])->name('replenishments.export');
            Route::get('replenishments/create', [App\Http\Controllers\BO\ReplenishmentController::class, 'create'])->name('replenishments.create');
            Route::post('replenishments', [App\Http\Controllers\BO\ReplenishmentController::class, 'store'])->name('replenishments.store');
            Route::get('replenishments/{id}', [App\Http\Controllers\BO\ReplenishmentController::class, 'show'])->name('replenishments.show');
            Route::post('replenishments/{id}/status', [App\Http\Controllers\BO\ReplenishmentController::class, 'updateStatus'])->name('replenishments.update-status');
            // BO-only PDF downloads
            Route::get('replenishments/{id}/picking.pdf', [App\Http\Controllers\BO\ReplenishmentController::class, 'downloadPicking'])->name('replenishments.download-picking');
            Route::get('replenishments/{id}/delivery-note.pdf', [App\Http\Controllers\BO\ReplenishmentController::class, 'downloadDeliveryNote'])->name('replenishments.download-delivery-note');

            // Temporary legacy redirects from deprecated Supplier Purchase Orders module to Replenishments
            Route::redirect('purchase-orders', 'replenishments', 302);
            Route::any('purchase-orders/{any}', function () {
                return redirect()->route('bo.replenishments.index');
            })->where('any', '.*');
        });
    });

    // Front Office routes (franchisees)
    Route::middleware(['auth', 'verified', 'role:franchisee'])->prefix('fo')->name('fo.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\FO\DashboardController::class, 'index'])->name('dashboard');
        // Sales module
        Route::get('/sales', [App\Http\Controllers\FO\SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/create', [App\Http\Controllers\FO\SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [App\Http\Controllers\FO\SaleController::class, 'store'])->name('sales.store');
        Route::get('/sales/{sale}', [App\Http\Controllers\FO\SaleController::class, 'show'])->name('sales.show');
        // Reports module
        Route::get('/reports', [App\Http\Controllers\FO\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{reportPdf}/download', [App\Http\Controllers\FO\ReportController::class, 'download'])->name('reports.download');
        // Truck module
        Route::get('/truck', [App\Http\Controllers\FO\TruckController::class, 'show'])->name('truck.show');
        Route::post('/truck/maintenance-request', [App\Http\Controllers\FO\TruckController::class, 'requestMaintenance'])
            ->name('truck.maintenance-request');
            
        // Account module
        Route::get('/account', [App\Http\Controllers\FO\AccountController::class, 'edit'])->name('account.edit');
        Route::patch('/account', [App\Http\Controllers\FO\AccountController::class, 'update'])->name('account.update');
    });
    
    // Remove catch-all redirect to login to avoid redirect loops; let unmatched FO routes 404 instead

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
