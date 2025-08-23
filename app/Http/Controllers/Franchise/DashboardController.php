<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Franchise;
use App\Models\StockOrder;
use App\Models\CustomerOrder;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $franchise = $user->franchise;  // La franchise du user connecté

        // Indicateurs spécifiques au franchisé
        $truckCount     = $franchise->trucks()->count();
        $warehouseCount = $franchise->warehouses()->count();
        $totalOrders    = $franchise->stockOrders()->count();
        $pendingOrders  = $franchise->stockOrders()->where('stock_orders.status', 'pending')->count();
        // KPIs ventes (CA et nb de ventes sur 30 jours)
        $turnover30d = CustomerOrder::whereHas('truck', fn($q)=>$q->where('franchise_id',$franchise->id))
            ->where('ordered_at','>=', now()->subDays(30))
            ->sum('total_price');
        $salesCount30d = CustomerOrder::whereHas('truck', fn($q)=>$q->where('franchise_id',$franchise->id))
            ->where('ordered_at','>=', now()->subDays(30))
            ->count();

        return view('franchise.dashboard', compact(
            'truckCount', 'warehouseCount', 'totalOrders', 'pendingOrders', 'turnover30d', 'salesCount30d'
        ));
    }
}
