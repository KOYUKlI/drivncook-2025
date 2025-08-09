<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\FranchiseMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ComputeMonthlyCommissions;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        ComputeMonthlyCommissions::class,
    ])
    ->withSchedule(function (Schedule $schedule): void {
        // Compute previous month commissions on the 1st of each month at 02:00
        $schedule->command('commissions:compute')->monthlyOn(1, '02:00');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // Middlewares globaux ou de groupe par défaut (Laravel les gère déjà)
        // -> On ajoute nos alias personnalisés :
        $middleware->alias([
            'admin'     => AdminMiddleware::class,
            'franchise' => FranchiseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
