<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\{StockOrder,Supply,Supplier,Inventory,CustomerOrder,Payment,Commission,ComplianceKpi};
use App\Policies\{StockOrderPolicy,SupplyPolicy,SupplierPolicy,InventoryPolicy,CustomerOrderPolicy,PaymentPolicy,CommissionPolicy,ComplianceKpiPolicy};

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
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
