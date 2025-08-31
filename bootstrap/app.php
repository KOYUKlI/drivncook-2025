<?php

use App\Actions\GenerateMonthlySalesPdfs;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Mission 1: monthly sales PDFs generator (runs every 1st of the month at 02:00)
        $schedule->call(function () {
            app(GenerateMonthlySalesPdfs::class)->handle();
        })->monthlyOn(1, '02:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
