@php $isFr = str_starts_with(app()->getLocale(), 'fr'); @endphp
<div class="section">
  <div class="section-title">{{ __('pdf.monthly.sections.notes') }}</div>
  <ul class="summary">
    @if(!empty(($observations['zero_days'] ?? [])))
      <li class="muted">{{ count($observations['zero_days']) }} {{ $isFr ? 'jours à 0' : 'days with zero sales' }} —
        {{ collect($observations['zero_days'])->map($formatDate)->join(', ') }}</li>
    @endif
    @if(!empty(($observations['best_day'] ?? null)))
      <li>{{ $isFr ? 'Pic' : 'Peak' }}: {{ $formatDate($observations['best_day']['date']) }} — <span class="badge">{{ $formatMoney($observations['best_day']['total_cents']) }}</span></li>
    @endif
    @if(empty($observations))
      <li class="muted">{{ $isFr ? 'Aucune anomalie détectée.' : 'No anomalies detected.' }}</li>
    @endif
  </ul>
</div>
