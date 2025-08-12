<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\ComplianceKpi;

class ComplianceService
{
    public function computeMonth(string $yearMonth): void
    {
        [$year,$month] = explode('-', $yearMonth);
        $rows = DB::table('franchise_monthly_purchase_mix')
            ->where('year', (int)$year)
            ->where('month', (int)$month)
            ->get();
        foreach ($rows as $r) {
            ComplianceKpi::updateOrCreate([
                'franchise_id'=>$r->franchisee_id,
                'period_year'=>$r->year,
                'period_month'=>$r->month,
            ],[
                'external_turnover'=>$r->external_amount,
            ]);
        }
    }
}
