<div class="section">
  <div class="section-title">{{ __('pdf.monthly.daily_sales') }}</div>
  <table class="table-4col">
    <thead>
      <tr>
        <th>{{ __('pdf.monthly.headers.date') }}</th>
        <th class="num">{{ __('pdf.monthly.headers.transactions') }}</th>
        <th class="num">{{ __('pdf.monthly.headers.total') }}</th>
        <th class="num">{{ __('pdf.monthly.headers.avg') }}</th>
      </tr>
    </thead>
    <tbody>
    @foreach(($daily ?? []) as $row)
      <tr>
        <td>{{ $formatDate($row['date']) }}</td>
        <td class="num">{{ $row['transactions'] }}</td>
        <td class="num">{{ $formatMoney($row['total_cents']) }}</td>
        <td class="num">{{ $formatMoney($row['avg_ticket_cents']) }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
