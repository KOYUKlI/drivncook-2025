<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the front office dashboard for franchisees.
     */
    public function index()
    {
        $user = Auth::user();
        $franchiseeId = data_get($user, 'franchisee_id');

        // Calculate real franchisee dashboard data
        $currentMonth = now();
        $lastMonth = now()->subMonth();

        $currentMonthSales = \App\Models\Sale::whereBetween('created_at', [
            $currentMonth->startOfMonth(),
            $currentMonth->endOfMonth(),
        ])
            ->when($franchiseeId, fn ($q) => $q->where('franchisee_id', $franchiseeId))
            ->sum('total_cents');

        $lastMonthSales = \App\Models\Sale::whereBetween('created_at', [
            $lastMonth->startOfMonth(),
            $lastMonth->endOfMonth(),
        ])
            ->when($franchiseeId, fn ($q) => $q->where('franchisee_id', $franchiseeId))
            ->sum('total_cents');

        $salesGrowth = $lastMonthSales > 0 ?
            (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        $pendingOrders = \App\Models\PurchaseOrder::where('status', 'pending')
            ->when($franchiseeId, function ($q) {
                return $q->whereHas('warehouse', function ($subQ) {
                    // TODO: Add franchisee_id to warehouses or use proper relation
                    return $subQ;
                });
            })
            ->count();

        $userTruck = \App\Models\Truck::where('franchisee_id', $franchiseeId)->first();
        $truckStatus = $userTruck ? $userTruck->status : 'none';

        $recentSales = \App\Models\Sale::query()
            ->when($franchiseeId, fn ($q) => $q->where('franchisee_id', $franchiseeId))
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($sale) {
                return [
                    'date' => $sale->sale_date->format('Y-m-d'),
                    'amount' => $sale->total_cents,
                    'location' => 'Location non renseignée', // sale_date used instead
                ];
            })
            ->toArray();

        $data = [
            'monthly_sales' => $currentMonthSales,
            'sales_growth' => round($salesGrowth, 1),
            'pending_orders' => $pendingOrders,
            'truck_status' => $truckStatus,
            'recent_sales' => $recentSales,
            'quick_links' => [
                ['title' => 'Rapport mensuel PDF', 'route' => '#', 'icon' => 'document'],
                ['title' => 'Nouvelle commande', 'route' => 'fo.sales.create', 'icon' => 'plus'],
                ['title' => 'Mes rapports', 'route' => 'fo.reports.index', 'icon' => 'chart'],
            ],
        ];

        return view('fo.dashboard', compact('data', 'user'));
    }
}
