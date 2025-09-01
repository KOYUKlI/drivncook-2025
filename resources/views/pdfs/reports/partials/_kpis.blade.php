<div class="section">
  <div class="grid">
    <div class="col">
      <div class="kpi">
        <div class="value">{{ $formatMoney($kpis['total_cents'] ?? 0) }}</div>
        <div class="label">{{ __('pdf.monthly.kpis.total_sales') }}</div>
      </div>
    </div>
    <div class="col">
      <div class="kpi">
        <div class="value">{{ $kpis['transactions'] ?? 0 }}</div>
        <div class="label">{{ __('pdf.monthly.kpis.transactions') }}</div>
      </div>
    </div>
    <div class="col">
      <div class="kpi">
        <div class="value">{{ $formatMoney($kpis['avg_ticket_cents'] ?? 0) }}</div>
        <div class="label">{{ __('pdf.monthly.kpis.avg_ticket') }}</div>
      </div>
    </div>
    <div class="col">
      <div class="kpi">
        <div class="value">{{ $kpis['active_days'] ?? 0 }}</div>
        <div class="label">{{ __('pdf.monthly.kpis.active_days') }}</div>
      </div>
    </div>
  </div>
</div>
