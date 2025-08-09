<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\StockOrder;
use App\Models\Truck;
use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\InventoryService;

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
           ->with(['truck', 'warehouse', 'supplier'])
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
    $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get();
    return view('franchise.stockorders.create', compact('trucks', 'warehouses', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'truck_id'     => 'required|exists:trucks,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'supplier_id'  => 'nullable|exists:suppliers,id',
        ]);
        if (! $request->warehouse_id && ! $request->supplier_id) {
            return back()->withErrors(['warehouse_id' => 'Select a warehouse or a supplier', 'supplier_id' => 'Select a warehouse or a supplier'])->withInput();
        }
        if ($request->warehouse_id && $request->supplier_id) {
            return back()->withErrors(['warehouse_id' => 'Choose only one target', 'supplier_id' => 'Choose only one target'])->withInput();
        }
        $franchiseId = Auth::user()->franchise_id;
        // Vérifier que le truck et le warehouse appartiennent bien à la franchise du user
        $truck = Truck::findOrFail($request->truck_id);
    $warehouse = $request->warehouse_id ? Warehouse::findOrFail($request->warehouse_id) : null;
    if ($truck->franchise_id !== $franchiseId || ($warehouse && $warehouse->franchise_id !== $franchiseId)) {
            abort(403);
        }
        // Créer la commande de stock (statut initial "pending")
        StockOrder::create([
            'truck_id'     => $request->truck_id,
            'warehouse_id' => $request->warehouse_id,
            'supplier_id'  => $request->supplier_id,
            'status'       => 'pending',
            'ordered_at'   => now()
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
    $stockOrder->load(['items.supply', 'truck', 'warehouse','supplier']);
    $supplies = Supply::orderBy('name')->get();
    return view('franchise.stockorders.show', compact('stockOrder','supplies'));
    }

    public function complete(StockOrder $stockOrder, InventoryService $inventoryService)
    {
        if ($stockOrder->truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        if ($stockOrder->status !== 'pending') {
            return redirect()->route('franchise.stockorders.show', $stockOrder)
                             ->with('error', 'Cette commande n\'est pas modifiable.');
        }
        // mark completed and receive into inventory if warehouse target
        $stockOrder->status = 'completed';
        $stockOrder->save();
        $inventoryService->receiveStockOrder($stockOrder->load('items'));
        return redirect()->route('franchise.stockorders.show', $stockOrder)
                         ->with('success', 'Commande de stock terminée et réceptionnée.');
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
    $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get();
    return view('franchise.stockorders.edit', compact('stockOrder', 'trucks', 'warehouses', 'suppliers'));
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
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'supplier_id'  => 'nullable|exists:suppliers,id',
        ]);
        if (! $request->warehouse_id && ! $request->supplier_id) {
            return back()->withErrors(['warehouse_id' => 'Select a warehouse or a supplier', 'supplier_id' => 'Select a warehouse or a supplier'])->withInput();
        }
        if ($request->warehouse_id && $request->supplier_id) {
            return back()->withErrors(['warehouse_id' => 'Choose only one target', 'supplier_id' => 'Choose only one target'])->withInput();
        }
        // Vérifier de nouveau l'appartenance du truck/warehouse
        $franchiseId = Auth::user()->franchise_id;
        $truck = Truck::findOrFail($request->truck_id);
        $warehouse = $request->warehouse_id ? Warehouse::findOrFail($request->warehouse_id) : null;
        if ($truck->franchise_id !== $franchiseId || ($warehouse && $warehouse->franchise_id !== $franchiseId)) {
            abort(403);
        }
        $stockOrder->update([
            'truck_id'     => $request->truck_id,
            'warehouse_id' => $request->warehouse_id,
            'supplier_id'  => $request->supplier_id,
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
