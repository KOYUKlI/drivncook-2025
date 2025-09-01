<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h1 { font-size: 18px; margin-bottom: 8px; }
    .meta { margin-bottom: 12px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ddd; padding:6px; }
    th { background:#f3f3f3; text-align:left; }
  </style>
</head>
<body>
  <h1>{{ __('ui.replenishments.pdf.picking_title') }} - {{ $order->reference ?? $order->id }}</h1>
  <div class="meta">
    <div>{{ __('ui.common.franchisee') }}: {{ $order->franchisee->name ?? '-' }}</div>
    <div>{{ __('ui.common.warehouse') }}: {{ $order->warehouse->name ?? '-' }}</div>
    <div>{{ __('ui.common.created_at') }}: {{ optional($order->created_at)->format('Y-m-d H:i') }}</div>
  </div>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>{{ __('ui.common.item') }}</th>
        <th>{{ __('ui.common.qty') }}</th>
        <th>{{ __('ui.common.to_ship') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($lines as $i => $line)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $line->stockItem->name ?? '-' }}</td>
          <td>{{ $line->qty }}</td>
          <td>{{ max(0, $line->qty - ($line->qty_shipped ?? 0)) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
