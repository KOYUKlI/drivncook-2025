<div class="section break-before">
  <div class="section-title">{{ __('pdf.monthly.sections.top_products') }}</div>
  <table>
    <thead>
      <tr>
        <th>{{ __('pdf.monthly.headers.product') }}</th>
        <th class="num">{{ __('pdf.monthly.headers.qty') }}</th>
        <th class="num">{{ __('pdf.monthly.headers.sales') }}</th>
        <th class="num">{{ __('pdf.monthly.headers.share') }}</th>
      </tr>
    </thead>
    <tbody>
    @foreach(($topProducts ?? []) as $p)
      <tr>
        <td>{{ $p['name'] }}</td>
        <td class="num">{{ $p['qty'] }}</td>
        <td class="num">{{ $formatMoney($p['sales_cents']) }}</td>
        <td class="num">{{ number_format(($p['share'] ?? 0) * 100, 1, ($isFr ?? false) ? ',' : '.', ($isFr ?? false) ? ' ' : ',') }}%</td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
