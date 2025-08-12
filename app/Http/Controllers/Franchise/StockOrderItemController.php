<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\StockOrder;
use App\Models\StockOrderItem;
use Illuminate\Http\Request;
use App\Http\Requests\Franchise\StoreStockOrderItemRequest;
use Illuminate\Support\Facades\Auth;

class StockOrderItemController extends Controller
{
    public function store(StoreStockOrderItemRequest $request, StockOrder $stockorder)
    {
        // Authorization: ensure order belongs to current franchise and is pending
        if ($stockorder->truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        if ($stockorder->status !== 'pending') {
            return redirect()->route('franchise.stockorders.show', $stockorder)
                             ->with('error', 'This order can no longer be modified.');
        }

    $data = $request->validated();

        $item = new StockOrderItem($data);
        $item->stock_order_id = $stockorder->id;
        $item->save();

        return redirect()->route('franchise.stockorders.show', $stockorder)
                         ->with('success', 'Item added to order.');
    }

    public function destroy(StockOrder $stockorder, StockOrderItem $item)
    {
        if ($stockorder->truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        if ($stockorder->id !== $item->stock_order_id) {
            abort(404);
        }
        if ($stockorder->status !== 'pending') {
            return redirect()->route('franchise.stockorders.show', $stockorder)
                             ->with('error', 'This order can no longer be modified.');
        }
        $item->delete();
        return redirect()->route('franchise.stockorders.show', $stockorder)
                         ->with('success', 'Item removed from order.');
    }
}
