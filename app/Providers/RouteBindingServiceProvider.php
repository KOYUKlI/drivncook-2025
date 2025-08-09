<?php

namespace App\Providers;

use App\Models\MaintenanceRecord;
use App\Models\Franchise;
use App\Models\Location;
use App\Models\TruckDeployment;
use App\Models\StockOrder;
use App\Models\Truck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class RouteBindingServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Admin resource: franchisees bound by ULID
        \Illuminate\Support\Facades\Route::bind('franchisee', function ($value) {
            return Franchise::query()->where('ulid', $value)->firstOrFail();
        });

        // Admin resources: locations and deployments bound by ULID
        \Illuminate\Support\Facades\Route::bind('location', function ($value) {
            return Location::query()->where('ulid', $value)->firstOrFail();
        });
        \Illuminate\Support\Facades\Route::bind('deployment', function ($value) {
            return TruckDeployment::query()->where('ulid', $value)->firstOrFail();
        });

        Route::bind('truck', function ($value) {
            $query = Truck::query();
            if (Auth::check() && Auth::user()->role === 'franchise') {
                $query->where('franchise_id', Auth::user()->franchise_id);
            }
            // Only allow ULIDs; numeric IDs should not resolve
            return $query->where('ulid', $value)->firstOrFail();
        });

        Route::bind('stockorder', function ($value) {
            $query = StockOrder::query()->where('ulid', $value);
            if (Auth::check() && Auth::user()->role === 'franchise') {
                $query->whereHas('truck', function ($q) {
                    $q->where('franchise_id', Auth::user()->franchise_id);
                });
            }
            return $query->firstOrFail();
        });

        Route::bind('maintenance', function ($value) {
            $query = MaintenanceRecord::query()->where('ulid', $value);
            if (Auth::check() && Auth::user()->role === 'franchise') {
                $query->whereHas('truck', function ($q) {
                    $q->where('franchise_id', Auth::user()->franchise_id);
                });
            }
            return $query->firstOrFail();
        });
    }
}
