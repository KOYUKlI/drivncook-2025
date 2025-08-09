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
@endsection
