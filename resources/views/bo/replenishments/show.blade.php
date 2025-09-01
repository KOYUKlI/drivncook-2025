@extends('layouts.app-shell')

@section('sidebar')
  @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.breadcrumbs :items="[
  ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
  ['title' => __('ui.replenishments.title'), 'url' => route('bo.replenishments.index')],
  ['title' => __('ui.replenishments.show_title', ['ref'=> $order->reference ?? $order->id])]
]" />
<div class="p-6 max-w-6xl mx-auto">
  @php $status = strtoupper($order->status); @endphp
  <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between mb-6">
    <div>
      <div class="mb-1">
        <a class="text-sm text-gray-600 hover:underline" href="{{ route('bo.replenishments.index') }}">{{ __('ui.common.back') }}</a>
      </div>
      <h1 class="text-2xl font-semibold">{{ __('ui.replenishments.show_title', ['ref'=> $order->reference ?? $order->id]) }}</h1>
      <div class="mt-1 text-sm text-gray-600">
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
          {{ in_array($status, ['DRAFT']) ? 'bg-gray-100 text-gray-800' : '' }}
          {{ in_array($status, ['APPROVED']) ? 'bg-blue-100 text-blue-800' : '' }}
          {{ in_array($status, ['PICKED']) ? 'bg-amber-100 text-amber-800' : '' }}
          {{ in_array($status, ['SHIPPED']) ? 'bg-indigo-100 text-indigo-800' : '' }}
          {{ in_array($status, ['DELIVERED']) ? 'bg-green-100 text-green-800' : '' }}
          {{ in_array($status, ['CANCELLED']) ? 'bg-red-100 text-red-800' : '' }}
          {{ in_array($status, ['CLOSED']) ? 'bg-emerald-100 text-emerald-800' : '' }}
        ">
          {{ __('ui.replenishments.status.'.strtolower($order->status)) }}
        </span>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 items-center">
      @can('updateStatus', $order)
        @if($status === 'DRAFT')
          <form method="POST" action="{{ route('bo.replenishments.update-status', $order->id) }}">@csrf<input type="hidden" name="status" value="Approved"><button class="btn btn-primary" title="{{ __('ui.replenishments.actions.approve_title') }}">{{ __('ui.common.approve') }}</button></form>
          <form method="POST" action="{{ route('bo.replenishments.update-status', $order->id) }}">@csrf<input type="hidden" name="status" value="Cancelled"><button class="btn" title="{{ __('ui.replenishments.actions.cancel_title') }}">{{ __('ui.common.cancel') }}</button></form>
        @elseif($status === 'APPROVED')
          <form method="POST" action="{{ route('bo.replenishments.update-status', $order->id) }}">@csrf<input type="hidden" name="status" value="Picked"><button class="btn btn-secondary" title="{{ __('ui.replenishments.actions.prepare_title') }}">{{ __('ui.common.prepare') }}</button></form>
  @elseif($status === 'PICKED')
          
  @elseif($status === 'SHIPPED')
          
        @endif
      @endcan

      @can('view', $order)
        @if(in_array($status, ['PICKED','SHIPPED','DELIVERED','CLOSED']))
          <a class="btn btn-light" href="{{ route('bo.replenishments.download-picking', $order->id) }}">{{ __('ui.replenishments.pdf.download_picking') }}</a>
        @endif
        @if(in_array($status, ['SHIPPED','DELIVERED','CLOSED']))
          <a class="btn btn-light" href="{{ route('bo.replenishments.download-delivery-note', $order->id) }}">{{ __('ui.replenishments.pdf.download_delivery_note') }}</a>
        @endif
      @endcan
    </div>
  </div>

  @php
    $orderedTotal = $order->lines->sum('qty');
    $shippedTotal = $order->lines->sum(fn($l) => $l->qty_shipped ?? 0);
    $deliveredTotal = $order->lines->sum(fn($l) => $l->qty_delivered ?? 0);
    $toShipTotal = $order->lines->sum(fn($l) => max(0, $l->qty - ($l->qty_shipped ?? 0)));
    $toDeliverTotal = $order->lines->sum(fn($l) => max(0, ($l->qty_shipped ?? 0) - ($l->qty_delivered ?? 0)));
    $linesPartialShipped = $order->lines->filter(fn($l) => ($l->qty_shipped ?? 0) > 0 && ($l->qty_shipped ?? 0) < $l->qty)->count();
    $linesPartialDelivered = $order->lines->filter(fn($l) => ($l->qty_delivered ?? 0) > 0 && ($l->qty_delivered ?? 0) < ($l->qty_shipped ?? 0))->count();
    $totalCents = $order->lines->sum(fn($l) => (int) ($l->unit_price_cents ?? 0));
  @endphp

  <div class="mb-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-4 gap-3">
      <div class="p-3 border rounded bg-white">
        <div class="text-xs text-gray-500">{{ __('ui.replenishments.summary.ordered_total') }}</div>
        <div class="font-semibold">{{ $orderedTotal }}</div>
      </div>
      <div class="p-3 border rounded bg-white">
        <div class="text-xs text-gray-500">{{ __('ui.replenishments.summary.shipped_total') }}</div>
        <div class="font-semibold">{{ $shippedTotal }} <span class="text-xs text-gray-500">({{ __('ui.replenishments.summary.to_ship') }}: {{ $toShipTotal }})</span></div>
      </div>
      <div class="p-3 border rounded bg-white">
        <div class="text-xs text-gray-500">{{ __('ui.replenishments.summary.delivered_total') }}</div>
        <div class="font-semibold">{{ $deliveredTotal }} <span class="text-xs text-gray-500">({{ __('ui.replenishments.summary.to_deliver') }}: {{ $toDeliverTotal }})</span></div>
      </div>
      <div class="p-3 border rounded bg-white">
        <div class="text-xs text-gray-500">{{ __('ui.replenishments.summary.partials') }}</div>
        <div class="font-semibold">{{ __('ui.replenishments.summary.lines_partial', ['shipped' => $linesPartialShipped, 'delivered' => $linesPartialDelivered]) }}</div>
      </div>
    </div>
    <div class="p-4 border rounded bg-white">
      <div class="text-xs text-gray-500">{{ __('ui.labels.total') }}</div>
      <div class="text-xl font-semibold">{{ number_format($totalCents/100, 2, '.', ' ') }} €</div>
      <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
        <div>
          <div class="text-gray-500 text-xs">{{ __('ui.common.warehouse') }}</div>
          <div>{{ $order->warehouse->name ?? '-' }}</div>
        </div>
        <div>
          <div class="text-gray-500 text-xs">{{ __('ui.common.franchisee') }}</div>
          <div>{{ $order->franchisee->name ?? '-' }}</div>
        </div>
        <div>
          <div class="text-gray-500 text-xs">{{ __('ui.common.created_at') }}</div>
          <div>{{ $order->created_at->format('Y-m-d H:i') }}</div>
        </div>
      </div>
    </div>
  </div>

  @if($status === 'PICKED')
    @can('updateStatus', $order)
    @php $anyShippable = $order->lines->some(fn($l) => max(0, $l->qty - ($l->qty_shipped ?? 0)) > 0); @endphp
    <form id="ship-section" method="POST" action="{{ route('bo.replenishments.update-status', $order->id) }}" class="mb-6 border rounded bg-white">
      @csrf
      <input type="hidden" name="status" value="Shipped">
      <div class="p-3 border-b font-medium">{{ __('ui.replenishments.actions.ship_title') }}</div>
      <div class="overflow-x-auto p-3">
        <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left text-gray-600">
          <th class="p-2">{{ __('ui.common.item') }}</th>
          <th class="p-2">{{ __('ui.common.qty') }}</th>
          <th class="p-2">{{ __('ui.common.shipped') }}</th>
          <th class="p-2">{{ __('ui.common.delivered') }}</th>
          <th class="p-2">{{ __('ui.common.to_ship') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->lines as $line)
        <tr class="border-t">
          <td class="p-2">{{ $line->stockItem->name ?? '-' }}</td>
          <td class="p-2">{{ $line->qty }}</td>
          <td class="p-2">{{ $line->qty_shipped ?? 0 }}</td>
          <td class="p-2">{{ $line->qty_delivered ?? 0 }}</td>
          <td class="p-2">
            <input type="number" min="0" class="form-input w-24" name="ship[lines][{{ $line->id }}][qty_shipped]" value="{{ old('ship.lines.'.$line->id.'.qty_shipped', max(0, $line->qty - ($line->qty_shipped ?? 0))) }}">
            @error('ship.lines.'.$line->id.'.qty_shipped')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
      </div>
      <div class="mt-2 p-3 border-t bg-gray-50">
        <button class="btn btn-primary {{ $anyShippable ? '' : 'opacity-50 cursor-not-allowed' }}" {{ $anyShippable ? '' : 'disabled' }} title="{{ __('ui.replenishments.actions.ship_title') }}">{{ __('ui.common.ship') }}</button>
      </div>
    </form>
    @endcan
  @elseif($status === 'SHIPPED')
    @can('updateStatus', $order)
    @php $anyReceivable = $order->lines->some(fn($l) => max(0, ($l->qty_shipped ?? 0) - ($l->qty_delivered ?? 0)) > 0); @endphp
    <form id="receive-section" method="POST" action="{{ route('bo.replenishments.update-status', $order->id) }}" class="mb-6 border rounded bg-white">
      @csrf
      <input type="hidden" name="status" value="Delivered">
      <div class="p-3 border-b font-medium">{{ __('ui.replenishments.actions.receive_title') }}</div>
      <div class="overflow-x-auto p-3">
        <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left text-gray-600">
          <th class="p-2">{{ __('ui.common.item') }}</th>
          <th class="p-2">{{ __('ui.common.qty') }}</th>
          <th class="p-2">{{ __('ui.common.shipped') }}</th>
          <th class="p-2">{{ __('ui.common.delivered') }}</th>
          <th class="p-2">{{ __('ui.common.to_receive') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->lines as $line)
        <tr class="border-t">
          <td class="p-2">{{ $line->stockItem->name ?? '-' }}</td>
          <td class="p-2">{{ $line->qty }}</td>
          <td class="p-2">{{ $line->qty_shipped ?? 0 }}</td>
          <td class="p-2">{{ $line->qty_delivered ?? 0 }}</td>
          <td class="p-2">
            <input type="number" min="0" class="form-input w-24" name="receive[lines][{{ $line->id }}][qty_delivered]" value="{{ old('receive.lines.'.$line->id.'.qty_delivered', max(0, ($line->qty_shipped ?? 0) - ($line->qty_delivered ?? 0))) }}">
            @error('receive.lines.'.$line->id.'.qty_delivered')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
      </div>
      <div class="mt-2 p-3 border-t bg-gray-50">
        <button class="btn btn-primary {{ $anyReceivable ? '' : 'opacity-50 cursor-not-allowed' }}" {{ $anyReceivable ? '' : 'disabled' }} title="{{ __('ui.replenishments.actions.receive_title') }}">{{ __('ui.common.receive') }}</button>
      </div>
    </form>
  @endcan
  @else
    <div class="overflow-x-auto mb-6">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left text-gray-600">
            <th class="p-2">{{ __('ui.common.item') }}</th>
            <th class="p-2">{{ __('ui.common.qty') }}</th>
            <th class="p-2">{{ __('ui.common.shipped') }}</th>
            <th class="p-2">{{ __('ui.common.delivered') }}</th>
            <th class="p-2">{{ __('ui.labels.price') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->lines as $line)
          <tr class="border-t">
            <td class="p-2">{{ $line->stockItem->name ?? '-' }}</td>
            <td class="p-2">{{ $line->qty }}</td>
            <td class="p-2">{{ $line->qty_shipped ?? 0 }}</td>
            <td class="p-2">{{ $line->qty_delivered ?? 0 }}</td>
            <td class="p-2">{{ number_format(($line->unit_price_cents ?? 0)/100, 2, '.', ' ') }} €</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif


</div>
@endsection
