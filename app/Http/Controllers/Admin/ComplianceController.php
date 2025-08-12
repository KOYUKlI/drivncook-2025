<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplianceKpi;
use App\Models\CustomerOrder;
use App\Models\Franchise;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    public function index(Request $request)
    {
        $year = (int)($request->input('year', now()->year));
        $month = (int)($request->input('month', now()->month));

        $franchises = Franchise::orderBy('name')->get();
        $officialByFranchise = CustomerOrder::query()
            ->selectRaw('trucks.franchise_id, ROUND(SUM(customer_orders.total_price), 2) as total')
            ->join('trucks', 'trucks.id', '=', 'customer_orders.truck_id')
            ->whereYear('customer_orders.ordered_at', $year)
            ->whereMonth('customer_orders.ordered_at', $month)
            ->where('customer_orders.status', 'completed')
            ->where('customer_orders.payment_status', 'paid')
            ->groupBy('trucks.franchise_id')
            ->pluck('total', 'trucks.franchise_id');

        $kpis = ComplianceKpi::where('period_year', $year)->where('period_month', $month)->get()->keyBy('franchise_id');

        $rows = $franchises->map(function ($f) use ($officialByFranchise, $kpis, $year, $month) {
            $official = (float) ($officialByFranchise[$f->id] ?? 0);
            $external = (float) ($kpis[$f->id]->external_turnover ?? 0);
            $ratio = ($official + $external) > 0 ? round(($official / ($official + $external)) * 100, 2) : null;
            return compact('f','official','external','ratio');
        });

        return view('admin.compliance.index', compact('rows','year','month'));
    }

    public function edit(Request $request, Franchise $franchise)
    {
        $year = (int)($request->input('year', now()->year));
        $month = (int)($request->input('month', now()->month));
        $kpi = ComplianceKpi::firstOrNew([
            'franchise_id' => $franchise->id,
            'period_year' => $year,
            'period_month' => $month,
        ]);
        return view('admin.compliance.edit', compact('franchise','kpi','year','month'));
    }

    public function update(Request $request, Franchise $franchise)
    {
        $data = $request->validate(['external_turnover' => 'required|numeric|min:0', 'year' => 'required|integer', 'month' => 'required|integer|min:1|max:12']);
        ComplianceKpi::updateOrCreate([
            'franchise_id' => $franchise->id,
            'period_year' => $data['year'],
            'period_month' => $data['month'],
        ], ['external_turnover' => $data['external_turnover']]);

        return redirect()->route('admin.compliance.index', ['year' => $data['year'], 'month' => $data['month']])
            ->with('success', 'External turnover updated.');
    }
}
