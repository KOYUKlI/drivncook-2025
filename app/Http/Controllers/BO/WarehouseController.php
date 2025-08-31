<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Warehouse::class);
        
        $query = Warehouse::query();
        
        // Apply status filter if provided
        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Apply search if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
            });
        }
        
        $warehouses = $query->orderBy('name')->paginate(15);

        return view('bo.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        $this->authorize('create', Warehouse::class);
        
        return view('bo.warehouses.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Warehouse::class);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:warehouses,code',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:64',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:255',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $warehouse = new Warehouse($data);
        $warehouse->id = (string) Str::ulid();
        $warehouse->is_active = $data['is_active'] ?? true;
        $warehouse->save();

        return redirect()->route('bo.warehouses.index')->with('success', __('ui.bo.warehouses.messages.created'));
    }

    public function edit(Warehouse $warehouse)
    {
        $this->authorize('update', $warehouse);
        
        return view('bo.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $this->authorize('update', $warehouse);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:warehouses,code,'.$warehouse->id.',id',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:64',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:255',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $warehouse->update($data);

        return redirect()->route('bo.warehouses.index')->with('success', __('ui.bo.warehouses.messages.updated'));
    }

    public function destroy(Warehouse $warehouse)
    {
        $this->authorize('delete', $warehouse);
        
        $warehouse->delete();

        return redirect()->route('bo.warehouses.index')->with('success', __('ui.bo.warehouses.messages.deleted'));
    }
}
