<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the back office dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Calculate real dashboard data
        $lastMonth = now()->subMonth();
        $lastMonthSales = \App\Models\Sale::whereBetween('created_at', [
            $lastMonth->startOfMonth(),
            $lastMonth->endOfMonth(),
        ])->sum('total_cents');

        $complianceRatio = \App\Models\PurchaseOrder::whereNotNull('corp_ratio_cached')
            ->avg('corp_ratio_cached') ?? 0;

        $trucksInMaintenance = \App\Models\Truck::where('status', 'maintenance')->count();

        $pendingOrders = \App\Models\PurchaseOrder::where('status', 'pending')->count();

        $data = [
            'revenue_last_month' => $lastMonthSales,
            'compliance_ratio' => round($complianceRatio),
            'trucks_in_maintenance' => $trucksInMaintenance,
            'pending_orders' => $pendingOrders,
            'recent_events' => [
                // TODO: Implement real activity log
                ['type' => 'sale', 'description' => 'Activité récente', 'time' => 'Aujourd\'hui'],
                ['type' => 'maintenance', 'description' => 'Maintenances en cours', 'time' => 'Cette semaine'],
                ['type' => 'order', 'description' => 'Commandes en attente', 'time' => 'En cours'],
            ],
        ];

        return view('bo.dashboard', compact('data', 'user'));
    }
}
