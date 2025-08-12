@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Inventory Detail</h1>
  <a href="{{ route('admin.inventory.index') }}" class="btn">Back</a>
</div>
<div class="card p-4 space-y-2">
  <div><span class="font-medium">Warehouse:</span> {{ $inventory->warehouse->name ?? '—' }}</div>
  <div><span class="font-medium">Supply:</span> {{ $inventory->supply->name ?? '—' }}</div>
  <div><span class="font-medium">On hand:</span> {{ number_format($inventory->on_hand, 3) }}</div>
</div>

<div class="card p-4 mt-4">
  <h2 class="text-lg font-semibold mb-2">Recent movements</h2>
  <table class="data-table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Qty</th>
        <th>Reason</th>
        <th>Ref</th>
      </tr>
    </thead>
    <tbody>
      @forelse($movements as $m)
        <tr>
          <td>{{ \Carbon\Carbon::parse($m->created_at)->format('Y-m-d H:i') }}</td>
          <td>{{ $m->type }}</td>
          <td>{{ number_format($m->qty, 3) }}</td>
          <td>{{ $m->reason }}</td>
          <td>{{ $m->ref_table }}#{{ $m->ref_id }}</td>
        </tr>
      @empty
        <tr><td colspan="5" class="px-4 py-6 text-center text-gray-600">No movements.</td></tr>
      @endforelse
    </tbody>
  </table>
 </div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
  <div class="card p-4">
    <h2 class="text-lg font-semibold mb-2">Lots</h2>
    <table class="data-table mb-3">
      <thead><tr><th>Code</th><th>Expires</th><th>Qty</th><th></th></tr></thead>
      <tbody>
      @forelse($inventory->lots as $lot)
        <tr>
          <td>{{ $lot->lot_code }}</td>
          <td>{{ $lot->expires_at?->format('Y-m-d') }}</td>
          <td>{{ number_format($lot->qty,3) }}</td>
          <td class="text-right flex gap-1">
            <a class="btn-xs" href="{{ route('admin.inventory.lots.edit', [$inventory, $lot]) }}">Edit</a>
            <form method="POST" action="{{ route('admin.inventory.lots.destroy', [$inventory,$lot]) }}" onsubmit="return confirm('Delete lot?')">
              @csrf @method('DELETE')
              <button class="btn-xs btn-danger">✕</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center py-4 text-gray-500">No lots.</td></tr>
      @endforelse
      </tbody>
    </table>
    <form method="POST" action="{{ route('admin.inventory.lots.store', $inventory) }}" class="space-y-2">
      @csrf
      <div>
        <label class="form-label">Lot code</label>
        <input name="lot_code" class="form-input w-full" required />
      </div>
      <div class="flex gap-2">
        <div class="flex-1">
          <label class="form-label">Qty</label>
          <input name="qty" type="number" step="0.001" min="0.001" class="form-input w-full" required />
        </div>
        <div class="flex-1">
          <label class="form-label">Expires</label>
          <input name="expires_at" type="date" class="form-input w-full" />
        </div>
      </div>
      <button class="btn-primary w-full">Add Lot</button>
    </form>
  </div>
  <div class="card p-4">
    <h2 class="text-lg font-semibold mb-2">Adjust Quantity</h2>
    <form method="POST" action="{{ route('admin.inventory.adjust') }}" class="space-y-2">
      @csrf
      <input type="hidden" name="inventory_id" value="{{ $inventory->id }}" />
      <div>
        <label class="form-label">Difference (+/-)</label>
        <input name="qty_diff" type="number" step="0.001" class="form-input w-full" required />
      </div>
      <div>
        <label class="form-label">Reason</label>
        <select name="reason" class="form-select w-full" required>
          <option value="waste">Waste</option>
          <option value="breakage">Breakage</option>
          <option value="audit">Audit</option>
        </select>
      </div>
      <div>
        <label class="form-label">Note</label>
        <input name="note" class="form-input w-full" />
      </div>
      <button class="btn-secondary w-full">Apply Adjustment</button>
    </form>
  </div>
  <div class="card p-4">
    <h2 class="text-lg font-semibold mb-2">Transfer</h2>
    <form method="POST" action="{{ route('admin.inventory.move') }}" class="space-y-2">
      @csrf
      <input type="hidden" name="from_inventory_id" value="{{ $inventory->id }}" />
      <div>
        <label class="form-label">Destination</label>
        <select name="to_inventory_id" class="form-select w-full" required>
          @foreach($destinations as $dest)
            <option value="{{ $dest->id }}">{{ $dest->warehouse->name }} ({{ number_format($dest->on_hand,3) }})</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="form-label">Qty</label>
        <input name="qty" type="number" step="0.001" min="0.001" class="form-input w-full" required />
      </div>
      <button class="btn w-full">Transfer</button>
    </form>
  </div>
</div>
@endsection
