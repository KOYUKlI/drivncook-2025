<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Franchise;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::with('franchise')->get();
        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $franchises = Franchise::all();
        return view('admin.warehouses.create', compact('franchises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'location'     => 'required|string|max:255',
            'franchise_id' => 'required|exists:franchises,id'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        return view('admin.warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        $franchises = Franchise::all();
        return view('admin.warehouses.edit', compact('warehouse', 'franchises'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'location'     => 'required|string|max:255',
            'franchise_id' => 'required|exists:franchises,id'
        ]);
        $warehouse->update($request->only('name', 'location', 'franchise_id'));
        return redirect()->route('admin.warehouses.index')
                         ->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('admin.warehouses.index')
                         ->with('success', 'Warehouse deleted successfully.');
    }
}
