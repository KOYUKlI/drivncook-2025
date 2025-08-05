<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use App\Models\Warehouse;
use App\Models\Franchise;
use App\Models\CustomerOrder;
use App\Models\StockOrder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Calcul des indicateurs globaux
        $truckCount      = Truck::count();
        $warehouseCount  = Warehouse::count();
        $franchiseCount  = Franchise::count();
        $totalSalesCount = CustomerOrder::count();
        $totalSalesSum   = CustomerOrder::sum('total_price');   // montant total des ventes
        $pendingStockOrders = StockOrder::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'truckCount', 'warehouseCount', 'franchiseCount', 
            'totalSalesCount', 'totalSalesSum', 'pendingStockOrders'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**public function store(Request $request)
    {
        //
    }*/

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**public function update(Request $request, string $id)
    {
        //
    }*/

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
