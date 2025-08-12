<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Services\ComplianceService;

class ComputePurchaseMix extends Command
{
    protected $signature = 'purchase-mix:compute {--year=} {--month=}';
    protected $description = 'Compute monthly 80/20 purchase mix compliance KPIs';

    public function handle(ComplianceService $service): int
    {
        $year = (int)($this->option('year') ?: Carbon::now()->copy()->subMonth()->year);
        $month = (int)($this->option('month') ?: Carbon::now()->copy()->subMonth()->month);
        $period = sprintf('%04d-%02d', $year, $month);
        $this->info("Computing purchase mix for $period");
        $service->computeMonth($period);
        $this->info('Done.');
        return Command::SUCCESS;
    }
}
