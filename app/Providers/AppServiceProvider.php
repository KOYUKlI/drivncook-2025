<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment(['local', 'testing'])) {
            try {
                foreach (['admin', 'warehouse', 'fleet', 'tech', 'franchisee', 'applicant'] as $r) {
                    Role::findOrCreate($r, 'web');
                }
            } catch (\Throwable $e) {
                // ok si tables pas encore migrées
            }
        }
    }
}
