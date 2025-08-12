<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInventoryLotRequest;
use App\Http\Requests\Admin\UpdateInventoryLotRequest;
use App\Models\Inventory;
use App\Models\InventoryLot;
use App\Models\InventoryMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryLotController extends Controller
{
    public function create(Inventory $inventory): View
    {
        return view('admin.inventory.lots.create', compact('inventory'));
    }

    public function store(StoreInventoryLotRequest $request, Inventory $inventory): RedirectResponse
    {
        $data = $request->validated();
        DB::transaction(function () use ($inventory, $data) {
            $lot = $inventory->lots()->create([
                'lot_code' => $data['lot_code'],
                'qty' => $data['qty'],
                'expires_at' => $data['expires_at'] ?? null,
            ]);
            $inventory->increment('on_hand', $data['qty']);
            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'type' => 'in',
                'qty' => $data['qty'],
                'reason' => 'adjust',
                'ref_table' => 'inventory_lots',
                'ref_id' => $lot->id,
                'created_at' => now(),
            ]);
        });
        return redirect()->route('admin.inventory.show', $inventory)->with('success', 'Lot created.');
    }

    public function edit(Inventory $inventory, InventoryLot $lot): View
    {
        if ($lot->inventory_id !== $inventory->id) abort(404);
        return view('admin.inventory.lots.edit', compact('inventory','lot'));
    }

    public function update(UpdateInventoryLotRequest $request, Inventory $inventory, InventoryLot $lot): RedirectResponse
    {
        if ($lot->inventory_id !== $inventory->id) abort(404);
        $data = $request->validated();
        DB::transaction(function () use ($inventory, $lot, $data) {
            $delta = $data['qty'] - $lot->qty;
            $lot->update([
                'lot_code' => $data['lot_code'],
                'qty' => $data['qty'],
                'expires_at' => $data['expires_at'] ?? null,
            ]);
            if (abs($delta) > 0.0001) {
                $inventory->on_hand += $delta;
                $inventory->save();
                InventoryMovement::create([
                    'inventory_id' => $inventory->id,
                    'type' => $delta >= 0 ? 'in' : 'out',
                    'qty' => abs($delta),
                    'reason' => 'adjust',
                    'ref_table' => 'inventory_lots',
                    'ref_id' => $lot->id,
                    'created_at' => now(),
                ]);
            }
        });
        return redirect()->route('admin.inventory.show', $inventory)->with('success', 'Lot updated.');
    }

    public function destroy(Inventory $inventory, InventoryLot $lot): RedirectResponse
    {
        if ($lot->inventory_id !== $inventory->id) abort(404);
        DB::transaction(function () use ($inventory, $lot) {
            $qty = $lot->qty;
            $lot->delete();
            $inventory->decrement('on_hand', $qty);
            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'type' => 'out',
                'qty' => $qty,
                'reason' => 'adjust',
                'ref_table' => 'inventory_lots',
                'ref_id' => null,
                'created_at' => now(),
            ]);
        });
        return redirect()->route('admin.inventory.show', $inventory)->with('success', 'Lot deleted.');
    }
}
