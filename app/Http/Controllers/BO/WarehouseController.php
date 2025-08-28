<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::orderBy('name')->get();

        return view('bo.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('bo.warehouses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $warehouse = new Warehouse($data);
        $warehouse->id = (string) Str::ulid();
        $warehouse->save();

        return redirect()->route('bo.warehouses.index')->with('success', __('ui.bo.warehouses.messages.created'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('bo.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $warehouse->update($data);

        return redirect()->route('bo.warehouses.index')->with('success', __('ui.bo.warehouses.messages.updated'));
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('bo.warehouses.index')->with('success', __('ui.bo.warehouses.messages.deleted'));
    }
}
