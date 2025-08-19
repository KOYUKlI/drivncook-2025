@extends('layouts.app')
@section('content')
<div class="p-4">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Commissions</h1>
    <form method="GET" class="flex items-end gap-2">
      <label class="form-control">
        <span class="form-label">Year</span>
        <input type="number" min="2000" name="year" class="input" value="{{ $year }}" placeholder="2025" />
      </label>
      <label class="form-control">
        <span class="form-label">Month</span>
        <select name="month" class="form-select">
          @php($months = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'])
          @foreach($months as $mVal=>$mLabel)
            <option value="{{ $mVal }}" @selected((int)$month === $mVal)>{{ $mLabel }}</option>
          @endforeach
        </select>
      </label>
      <button class="btn btn-secondary" type="submit">Filter</button>
    </form>
  </div>
  <div class="card overflow-x-auto">
    <table class="data-table w-full">
      <thead>
        <tr>
          <th>Period</th>
          <th>Franchisee</th>
          <th>Turnover</th>
          <th>Rate</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($commissions as $c)
        <tr>
          <td>{{ $c->period_year }}-{{ str_pad($c->period_month, 2, '0', STR_PAD_LEFT) }}</td>
          <td>{{ $c->franchisee->name ?? ('#'.$c->franchisee_id) }}</td>
          <td>€ {{ number_format($c->turnover, 2) }}</td>
          <td>{{ number_format($c->rate, 2) }}%</td>
          <td>€ {{ number_format($c->turnover * ($c->rate/100), 2) }}</td>
          <td>
            <span class="badge {{ $c->status === 'paid' ? 'badge-success' : ($c->status==='canceled' ? 'badge' : 'badge-info') }}">{{ $c->status }}</span>
          </td>
          <td class="space-x-1">
            <a class="btn btn-sm" href="{{ route('admin.commissions.show', $c) }}">Open</a>
            <form class="inline" method="POST" action="{{ route('admin.commissions.update', $c) }}">
              @csrf @method('PUT')
              <input type="hidden" name="action" value="mark_paid" />
              <button class="btn btn-success btn-sm" @disabled($c->status==='paid')>Mark paid</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $commissions->links() }}</div>
</div>
@endsection
