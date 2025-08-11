@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="page-title">Saisie chiffre d'affaires externe</h1>
  <div>
    <a class="btn-secondary" href="{{ route('admin.compliance.index', ['year' => $year, 'month' => $month]) }}">Retour</a>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <div class="mb-4 text-sm text-gray-600">
      Franchise: <strong>{{ $franchise->name }}</strong> — Période: <strong>{{ sprintf('%02d/%04d', $month, $year) }}</strong>
    </div>
    <form method="POST" action="{{ route('admin.compliance.update', ['franchisee' => $franchise, 'year' => $year, 'month' => $month]) }}" class="space-y-4">
      @csrf
      @method('PUT')
  <input type="hidden" name="year" value="{{ $year }}">
  <input type="hidden" name="month" value="{{ $month }}">
      <div>
        <label class="form-label">Chiffre d'affaires externe (€)</label>
        <input type="number" step="0.01" name="external_turnover" class="form-input" value="{{ old('external_turnover', $kpi->external_turnover) }}">
        @error('external_turnover')
          <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
      </div>
      <div class="flex gap-2">
        <button class="btn-primary" type="submit">Enregistrer</button>
        <a class="btn-link" href="{{ route('admin.compliance.index', ['year' => $year, 'month' => $month]) }}">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection
