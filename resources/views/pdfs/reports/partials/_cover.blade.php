@php
  $isFr = str_starts_with(app()->getLocale(), 'fr');
  $logoPath = public_path('storage/brand/logo.pdf.png');
@endphp
<div class="cover">
  <div class="grid">
    <div class="col" style="width: 70%">
      <h1 class="brand">{{ __('pdf.monthly.title') }}</h1>
      <h2>{{ $periodLabel }}</h2>
      <p class="muted">{{ __('pdf.monthly.period') }}: <strong>{{ $periodLabel }}</strong></p>
      <p class="muted">{{ $viewModel['franchisee_name'] ?? ($isFr ? 'Global' : 'Global') }}</p>
      <p class="small muted">{{ __('pdf.monthly.generated_at') }}: {{ \Carbon\Carbon::parse($viewModel['generated_at'])->format($isFr ? 'd/m/Y H:i' : 'Y-m-d H:i') }}</p>
      <ul class="summary">
        <li>{{ __('pdf.monthly.daily_sales') }}</li>
        @if(!empty($viewModel['per_truck'] ?? []))
          <li>{{ __('pdf.monthly.sections.per_truck') }}</li>
        @endif
        @if(!empty($viewModel['top_products'] ?? []))
          <li>{{ __('pdf.monthly.sections.top_products') }}</li>
        @endif
        <li>{{ __('pdf.monthly.sections.notes') }}</li>
      </ul>
    </div>
    <div class="col" style="width: 30%; text-align: right;">
      @if(file_exists($logoPath))
        <img src="{{ $logoPath }}" alt="Logo" style="height: 64px;">
      @endif
    </div>
  </div>
</div>
