@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">My Sales</h1>
    <a href="{{ route('franchise.sales.export.pdf') }}" target="_blank" class="btn-secondary">Export PDF</a>
  </div>

<div class="card">
  <div class="card-body p-0">
    <table class="data-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Truck</th>
          <th>Total (€)</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
      @foreach($orders as $o)
        <tr>
          <td>{{ \Carbon\Carbon::parse($o->ordered_at)->format('Y-m-d H:i') }}</td>
          <td>{{ optional($o->truck)->name }}</td>
          <td>{{ number_format($o->total_price ?? 0, 2) }}</td>
          <td>{{ $o->status }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endsection
