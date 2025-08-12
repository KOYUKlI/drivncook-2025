<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\FranchiseMiddleware;
use App\Http\Middleware\EnsureFranchiseAttached;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ComputeMonthlyCommissions;
use App\Console\Commands\ComputePurchaseMix;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        ComputeMonthlyCommissions::class,
        ComputePurchaseMix::class,
    ])
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('commissions:compute')->monthlyOn(1, '01:10');
        $schedule->command('purchase-mix:compute')->monthlyOn(1, '01:20');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // Middlewares globaux ou de groupe par défaut (Laravel les gère déjà)
        // -> On ajoute nos alias personnalisés :
        $middleware->alias([
            'admin'     => AdminMiddleware::class,
            'franchise' => FranchiseMiddleware::class,
            'franchise.attached' => EnsureFranchiseAttached::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
