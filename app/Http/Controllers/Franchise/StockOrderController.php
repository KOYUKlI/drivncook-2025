<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\StockOrder;
use App\Models\Truck;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $franchiseId = Auth::user()->franchise_id;
        // Récupérer toutes les commandes de stock de ce franchisé (avec camion et entrepôt)
        $orders = StockOrder::whereHas('truck', function($query) use ($franchiseId) {
                        $query->where('franchise_id', $franchiseId);
                   })
                   ->with(['truck', 'warehouse'])
                   ->orderByDesc('ordered_at')
                   ->get();
        return view('franchise.stockorders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $franchiseId = Auth::user()->franchise_id;
        $trucks = Truck::where('franchise_id', $franchiseId)->get();
        $warehouses = Warehouse::where('franchise_id', $franchiseId)->get();
        return view('franchise.stockorders.create', compact('trucks', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'truck_id'     => 'required|exists:trucks,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            // pas de validation de 'items' ici, les items seront ajoutés séparément si applicable
        ]);
        $franchiseId = Auth::user()->franchise_id;
        // Vérifier que le truck et le warehouse appartiennent bien à la franchise du user
        $truck = Truck::findOrFail($request->truck_id);
        $warehouse = Warehouse::findOrFail($request->warehouse_id);
        if ($truck->franchise_id !== $franchiseId || $warehouse->franchise_id !== $franchiseId) {
            abort(403);
        }
        // Créer la commande de stock (statut initial "pending")
        StockOrder::create([
            'truck_id'    => $request->truck_id,
            'warehouse_id'=> $request->warehouse_id,
            'status'      => 'pending',
            'ordered_at'  => now()
        ]);
        return redirect()->route('franchise.stockorders.index')
                         ->with('success', 'Stock order placed successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockOrder $stockOrder)
    {
        // S'assurer que la commande appartient à la franchise du user
        if ($stockOrder->truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        $stockOrder->load(['items.supply', 'truck', 'warehouse']);
        return view('franchise.stockorders.show', compact('stockOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockOrder $stockOrder)
    {
        if ($stockOrder->truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        // On ne permet l'édition que si la commande est encore "pending" (non traitée)
        if ($stockOrder->status !== 'pending') {
            return redirect()->route('franchise.stockorders.index')
                             ->with('error', 'This order can no longer be edited.');
        }
        $franchiseId = Auth::user()->franchise_id;
        $trucks = Truck::where('franchise_id', $franchiseId)->get();
        $warehouses = Warehouse::where('franchise_id', $franchiseId)->get();
        return view('franchise.stockorders.edit', compact('stockOrder', 'trucks', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockOrder $stockOrder)
    {
        if ($stockOrder->truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        if ($stockOrder->status !== 'pending') {
            return redirect()->route('franchise.stockorders.index')
                             ->with('error', 'This order can no longer be updated.');
        }
        $request->validate([
            'truck_id'     => 'required|exists:trucks,id',
            'warehouse_id' => 'required|exists:warehouses,id'
        ]);
        // Vérifier de nouveau l'appartenance du truck/warehouse
        $franchiseId = Auth::user()->franchise_id;
        $truck = Truck::findOrFail($request->truck_id);
        $warehouse = Warehouse::findOrFail($request->warehouse_id);
        if ($truck->franchise_id !== $franchiseId || $warehouse->franchise_id !== $franchiseId) {
            abort(403);
        }
        $stockOrder->update([
            'truck_id'     => $request->truck_id,
            'warehouse_id' => $request->warehouse_id
            // on ne change pas 'status' ni 'ordered_at' ici
        ]);
        return redirect()->route('franchise.stockorders.index')
                         ->with('success', 'Stock order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockOrder $stockOrder)
    {
        if ($stockOrder->truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        if ($stockOrder->status !== 'pending') {
            return redirect()->route('franchise.stockorders.index')
                             ->with('error', 'This order can no longer be canceled.');
        }
        $stockOrder->delete();
        return redirect()->route('franchise.stockorders.index')
                         ->with('success', 'Stock order canceled.');
    }
}
