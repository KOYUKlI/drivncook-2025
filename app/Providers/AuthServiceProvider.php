<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\{StockOrder,Supply,Supplier,Inventory,CustomerOrder,Payment,Commission,ComplianceKpi,TruckRequest};
use App\Policies\{StockOrderPolicy,SupplyPolicy,SupplierPolicy,InventoryPolicy,CustomerOrderPolicy,PaymentPolicy,CommissionPolicy,ComplianceKpiPolicy,TruckRequestPolicy};

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        StockOrder::class => StockOrderPolicy::class,
        Supply::class => SupplyPolicy::class,
        Supplier::class => SupplierPolicy::class,
        Inventory::class => InventoryPolicy::class,
        CustomerOrder::class => CustomerOrderPolicy::class,
        Payment::class => PaymentPolicy::class,
        Commission::class => CommissionPolicy::class,
        ComplianceKpi::class => ComplianceKpiPolicy::class,
    TruckRequest::class => TruckRequestPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
