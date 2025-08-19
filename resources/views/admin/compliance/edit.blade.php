@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Enter External Revenue</h1>
  <div>
  <a class="btn-secondary" href="{{ route('admin.compliance.index', ['year' => $year, 'month' => $month]) }}">Back</a>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <div class="mb-4 text-sm text-gray-600">
  Franchise: <strong>{{ $franchise->name }}</strong> — Period: <strong>{{ sprintf('%02d/%04d', $month, $year) }}</strong>
    </div>
    <form method="POST" action="{{ route('admin.compliance.update', ['franchisee' => $franchise, 'year' => $year, 'month' => $month]) }}" class="space-y-4">
      @csrf
      @method('PUT')
  <input type="hidden" name="year" value="{{ $year }}">
  <input type="hidden" name="month" value="{{ $month }}">
      <div>
  <label class="form-label">External Revenue (€)</label>
        <input type="number" step="0.01" name="external_turnover" class="form-input" value="{{ old('external_turnover', $kpi->external_turnover) }}">
        @error('external_turnover')
          <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
      </div>
      <div class="flex gap-2">
  <button class="btn-primary" type="submit">Save</button>
  <a class="btn-link" href="{{ route('admin.compliance.index', ['year' => $year, 'month' => $month]) }}">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
