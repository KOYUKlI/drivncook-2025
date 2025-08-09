@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Inventory</h1>
</div>
<div class="card p-4">
  <form method="GET" class="flex gap-3 flex-wrap mb-4">
    <div>
      <label class="form-label">Warehouse</label>
      <select name="warehouse_id" class="form-select">
        <option value="">All</option>
        @foreach($warehouses as $w)
          <option value="{{ $w->id }}" @selected($warehouseId==$w->id)>{{ $w->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="form-label">Supply</label>
      <select name="supply_id" class="form-select">
        <option value="">All</option>
        @foreach($supplies as $s)
          <option value="{{ $s->id }}" @selected($supplyId==$s->id)>{{ $s->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="self-end"><button class="btn-secondary">Filter</button></div>
  </form>

  <table class="data-table">
    <thead>
      <tr>
        <th>Warehouse</th>
        <th>Supply</th>
        <th>On hand</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $it)
        <tr>
          <td>{{ $it->warehouse->name ?? '—' }}</td>
          <td>{{ $it->supply->name ?? '—' }}</td>
          <td>{{ number_format($it->on_hand, 3) }}</td>
          <td class="text-right"><a class="btn-link" href="{{ route('admin.inventory.show', $it) }}">View</a></td>
        </tr>
      @empty
        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-600">No inventory.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="mt-3">{{ $items->links() }}</div>
</div>
@endsection
