<?php

namespace App\Console\Commands;

use App\Models\Commission;
use App\Models\Truck;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ComputeMonthlyCommissions extends Command
{
    protected $signature = 'commissions:compute {--year=} {--month=}';
    protected $description = 'Compute monthly commissions (4%) for each franchisee based on turnover';

    public function handle(): int
    {
        $year = (int)($this->option('year') ?: Carbon::now()->copy()->subMonth()->year);
        $month = (int)($this->option('month') ?: Carbon::now()->copy()->subMonth()->month);

        $periodStart = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $periodEnd = (clone $periodStart)->addMonth();

        $this->info("Computing commissions for {$year}-" . str_pad((string)$month, 2, '0', STR_PAD_LEFT));

        // fetch franchisee users
        $franchisees = User::query()->where('role', 'franchise')->whereNotNull('franchise_id')->get();
        $bar = $this->output->createProgressBar($franchisees->count());
        $bar->start();

        foreach ($franchisees as $user) {
            // trucks of the user's franchise
            $truckIds = Truck::query()->where('franchise_id', $user->franchise_id)->pluck('id');
            if ($truckIds->isEmpty()) {
                // upsert zero turnover entry so periods are present
                Commission::updateOrCreate(
                    [
                        'franchisee_id' => $user->id,
                        'period_year' => $year,
                        'period_month' => $month,
                    ],
                    [
                        'turnover' => 0,
                        'rate' => 4.00,
                        'status' => DB::raw("CASE WHEN status = 'paid' THEN status ELSE 'pending' END"),
                        'calculated_at' => now(),
                    ]
                );
                $bar->advance();
                continue;
            }

            // sum turnover for the month
            $turnover = DB::table('customer_orders')
                ->whereIn('truck_id', $truckIds)
                ->where('ordered_at', '>=', $periodStart)
                ->where('ordered_at', '<', $periodEnd)
                ->sum('total_price');

            Commission::updateOrCreate(
                [
                    'franchisee_id' => $user->id,
                    'period_year' => $year,
                    'period_month' => $month,
                ],
                [
                    'turnover' => $turnover,
                    'rate' => 4.00,
                    'status' => DB::raw("CASE WHEN status = 'paid' THEN status ELSE 'pending' END"),
                    'calculated_at' => now(),
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Commission computation done.');
        return self::SUCCESS;
    }
}
