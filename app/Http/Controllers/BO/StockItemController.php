<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StockItemController extends Controller
{
    public function index()
    {
        $items = StockItem::orderBy('name')->get();

        return view('bo.stock_items.index', compact('items'));
    }

    public function create()
    {
        return view('bo.stock_items.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'required|string|max:64|unique:stock_items,sku',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:32',
            'price_cents' => 'required|integer|min:0',
            'is_central' => 'sometimes|boolean',
        ]);

        $item = new StockItem($data);
        $item->id = (string) Str::ulid();
        $item->is_central = (bool) ($data['is_central'] ?? false);
        $item->save();

        return redirect()->route('bo.stock-items.index')->with('success', __('Article créé.'));
    }

    public function edit(StockItem $stock_item)
    {
        return view('bo.stock_items.edit', ['item' => $stock_item]);
    }

    public function update(Request $request, StockItem $stock_item)
    {
        $data = $request->validate([
            'sku' => 'required|string|max:64|unique:stock_items,sku,'.$stock_item->id.',id',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:32',
            'price_cents' => 'required|integer|min:0',
            'is_central' => 'sometimes|boolean',
        ]);

        $data['is_central'] = (bool) ($data['is_central'] ?? false);
        $stock_item->update($data);

        return redirect()->route('bo.stock-items.index')->with('success', __('Article mis à jour.'));
    }

    public function destroy(StockItem $stock_item)
    {
        $stock_item->delete();

        return redirect()->route('bo.stock-items.index')->with('success', __('Article supprimé.'));
    }
}
