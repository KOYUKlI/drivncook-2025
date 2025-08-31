<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StockItemController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', StockItem::class);
        
        $query = StockItem::query();
        
        // Apply status filter if provided
        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Apply type filter if provided
        if ($request->has('type') && in_array($request->type, ['central', 'local'])) {
            $query->where('is_central', $request->type === 'central');
        }
        
        // Apply search if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('unit', 'like', "%{$search}%");
            });
        }
        
        $items = $query->orderBy('name')->paginate(15);

        return view('bo.stock_items.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', StockItem::class);
        
        return view('bo.stock_items.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', StockItem::class);
        
        $data = $request->validate([
            'sku' => 'required|string|max:64|unique:stock_items,sku',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:32',
            'price_cents' => 'required|integer|min:0',
            'is_central' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $item = new StockItem($data);
        $item->id = (string) Str::ulid();
        $item->is_central = (bool) ($data['is_central'] ?? false);
        $item->is_active = (bool) ($data['is_active'] ?? true);
        $item->save();

        return redirect()->route('bo.stock-items.index')->with('success', __('ui.bo.stock_items.messages.created'));
    }

    public function edit(StockItem $stock_item)
    {
        $this->authorize('update', $stock_item);
        
        return view('bo.stock_items.edit', ['item' => $stock_item]);
    }

    public function update(Request $request, StockItem $stock_item)
    {
        $this->authorize('update', $stock_item);
        
        $data = $request->validate([
            'sku' => 'required|string|max:64|unique:stock_items,sku,'.$stock_item->id.',id',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:32',
            'price_cents' => 'required|integer|min:0',
            'is_central' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['is_central'] = (bool) ($data['is_central'] ?? false);
        $data['is_active'] = (bool) ($data['is_active'] ?? true);
        $stock_item->update($data);

        return redirect()->route('bo.stock-items.index')->with('success', __('ui.bo.stock_items.messages.updated'));
    }

    public function destroy(StockItem $stock_item)
    {
        $this->authorize('delete', $stock_item);
        
        $stock_item->delete();

        return redirect()->route('bo.stock-items.index')->with('success', __('ui.bo.stock_items.messages.deleted'));
    }
}
