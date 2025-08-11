@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Compliance 80/20</h1>
  <form method="GET" action="{{ route('admin.compliance.index') }}" class="flex items-center gap-2">
    <input type="number" name="year" class="form-input w-24" value="{{ $year }}">
    <input type="number" name="month" class="form-input w-20" value="{{ $month }}" min="1" max="12">
    <button class="btn-secondary" type="submit">Filter</button>
  </form>
  </div>
<div class="card"><div class="card-body p-0">
<table class="data-table">
  <thead><tr><th>Franchise</th><th>Officiel (€)</th><th>Externe (€)</th><th>Ratio</th><th class="text-center">Actions</th></tr></thead>
  <tbody>
    @foreach($rows as $row)
      <tr>
        <td>{{ $row['f']->name }}</td>
        <td>{{ number_format($row['official'], 2, ',', ' ') }}</td>
        <td>{{ number_format($row['external'], 2, ',', ' ') }}</td>
        <td>
          @if(!is_null($row['ratio']))
            <span class="badge {{ $row['ratio'] >= 80 ? 'badge-success':'badge-warning' }}">{{ $row['ratio'] }}%</span>
          @else
            —
          @endif
        </td>
        <td class="text-center">
          <a class="btn-link" href="{{ route('admin.compliance.edit', ['franchisee' => $row['f'], 'year' => $year, 'month' => $month]) }}">Edit externe</a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
</div></div>
@endsection
