<div class="section">
  <div class="section-title">{{ __('pdf.monthly.sections.per_truck') }}</div>
  <table>
    <thead>
      <tr>
        <th>{{ __('pdf.monthly.headers.truck') }}</th>
        <th class="num">{{ __('pdf.monthly.kpis.active_days') }}</th>
        <th class="num">{{ __('pdf.monthly.headers.sales') }}</th>
        <th class="num">{{ __('pdf.monthly.headers.share') }}</th>
      </tr>
    </thead>
    <tbody>
    @foreach(($perTruck ?? []) as $t)
      <tr>
        <td>{{ $t['name'] }}</td>
        <td class="num">{{ $t['active_days'] }}</td>
        <td class="num">{{ $formatMoney($t['total_cents']) }}</td>
        <td class="num">{{ number_format($t['share'] * 100, 1, str_starts_with(app()->getLocale(),'fr') ? ',' : '.', str_starts_with(app()->getLocale(),'fr') ? ' ' : ',') }}%</td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
