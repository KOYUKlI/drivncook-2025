<?php

use App\Actions\GenerateMonthlySalesPdfs;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Mission 1: monthly sales PDFs generator without Console\Kernel (Laravel 12 style)
Schedule::call(function () {
    app(GenerateMonthlySalesPdfs::class)->handle();
})->monthlyOn(1, '02:00');
