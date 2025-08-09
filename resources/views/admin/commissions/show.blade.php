@extends('layouts.app')
@section('content')
<div class="p-4 max-w-3xl">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Commission: {{ $commission->period_year }}-{{ str_pad($commission->period_month, 2, '0', STR_PAD_LEFT) }}</h1>
    <a class="btn" href="{{ route('admin.commissions.index') }}">Back</a>
  </div>
  <div class="card p-4 space-y-2">
    <div><span class="font-medium">Franchisee:</span> {{ $commission->franchisee->name ?? ('#'.$commission->franchisee_id) }}</div>
    <div><span class="font-medium">Turnover:</span> {{ number_format($commission->turnover, 2) }} €</div>
    <div><span class="font-medium">Rate:</span> {{ number_format($commission->rate, 2) }} %</div>
    <div><span class="font-medium">Amount:</span> {{ number_format($commission->turnover * ($commission->rate/100), 2) }} €</div>
    <div><span class="font-medium">Status:</span> {{ $commission->status }}</div>
    <div><span class="font-medium">Calculated at:</span> {{ optional($commission->calculated_at)->format('Y-m-d H:i') }}</div>
    <div><span class="font-medium">Paid at:</span> {{ optional($commission->paid_at)->format('Y-m-d H:i') ?: '—' }}</div>
  </div>
  <div class="mt-4 flex gap-2">
    <form method="POST" action="{{ route('admin.commissions.update', $commission) }}">
      @csrf @method('PUT')
      <input type="hidden" name="action" value="mark_paid" />
      <button class="btn btn-success" @disabled($commission->status==='paid')>Mark paid</button>
    </form>
    <form method="POST" action="{{ route('admin.commissions.update', $commission) }}">
      @csrf @method('PUT')
      <input type="hidden" name="action" value="pending" />
      <button class="btn btn-secondary">Mark pending</button>
    </form>
    <form method="POST" action="{{ route('admin.commissions.update', $commission) }}" onsubmit="return confirm('Cancel this commission?')">
      @csrf @method('PUT')
      <input type="hidden" name="action" value="cancel" />
      <button class="btn btn-danger">Cancel</button>
    </form>
  </div>
</div>
@endsection
