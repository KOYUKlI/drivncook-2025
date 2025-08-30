<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Using policy auto-discovery; explicit mappings can be added if needed.
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }

    public function shouldDiscoverPolicies(): bool
    {
        return true;
    }
}
