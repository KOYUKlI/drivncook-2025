<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupère toutes les ventes avec le camion et la franchise associée
        $orders = CustomerOrder::with('truck.franchise')->orderByDesc('ordered_at')->get();
        return view('admin.sales.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerOrder $order)
    {
        // Charger les items de la commande client (ex: plats vendus)
        $order->load(['items.dish', 'truck.franchise']);
        return view('admin.sales.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(404);
    }
}
