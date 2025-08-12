<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Franchise;
use App\Models\StockOrder;

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
        $truckCount    = $franchise->trucks()->count();
        $warehouseCount= $franchise->warehouses()->count();
        $totalOrders   = $franchise->stockOrders()->count();
    $pendingOrders = $franchise->stockOrders()->where('stock_orders.status', 'pending')->count();

        return view('franchise.dashboard', compact(
            'truckCount', 'warehouseCount', 'totalOrders', 'pendingOrders'
        ));
    }
}
