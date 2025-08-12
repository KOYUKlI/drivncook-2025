<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Commission;
use Carbon\Carbon;

class CommissionService
{
    public function computeMonth(string $yearMonth): void
    {
        [$year,$month] = explode('-', $yearMonth);
        $start = Carbon::createFromDate((int)$year,(int)$month,1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $rows = DB::table('customer_orders as o')
            ->join('trucks as t','t.id','=','o.truck_id')
            ->join('users as u', function($j){ $j->on('u.franchise_id','=','t.franchise_id')->where('u.role','franchise'); })
            ->select('u.id as franchisee_id', DB::raw('SUM(o.total_price) as turnover'))
            ->whereBetween('o.ordered_at', [$start,$end])
            ->where('o.status','completed')
            ->where('o.payment_status','paid')
            ->groupBy('u.id')
            ->get();
        foreach ($rows as $r) {
            Commission::updateOrCreate([
                'franchisee_id'=>$r->franchisee_id,
                'period_year'=>$start->year,
                'period_month'=>$start->month,
            ],[
                'turnover'=>$r->turnover,
                'rate'=>4.0,
                'status'=>'pending',
                'calculated_at'=>now(),
            ]);
        }
    }
}
