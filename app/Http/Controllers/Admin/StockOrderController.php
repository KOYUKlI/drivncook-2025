<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockOrder;
use Illuminate\Http\Request;

class StockOrderController extends Controller
{
    public function index() { return StockOrder::with('items')->paginate(); }
    public function show(StockOrder $stockOrder) { return $stockOrder->load('items'); }
    public function update(Request $request, StockOrder $stockOrder) { $stockOrder->update($request->only(['status'])); return $stockOrder; }
    public function destroy(StockOrder $stockOrder) { $stockOrder->delete(); return response()->noContent(); }
}
