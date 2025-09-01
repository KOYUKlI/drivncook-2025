@extends('layouts.app-shell')

@section('sidebar')
  @include('layouts.partials.sidebar')
@endsection

@section('content')
  <x-ui.breadcrumbs :items="[
    ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
    ['title' => __('ui.replenishments.title')]
  ]" />
<div class="p-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">{{ __('ui.replenishments.title') }}</h1>
    <div class="flex items-center gap-2">
      <a href="{{ route('bo.replenishments.export', request()->query()) }}" class="btn btn-secondary">{{ __('ui.actions.export') }}</a>
      <a href="{{ route('bo.replenishments.create') }}" class="btn btn-primary">{{ __('ui.replenishments.create') }}</a>
    </div>
  </div>

  <form method="GET" action="{{ route('bo.replenishments.index') }}" class="mb-4 bg-white p-3 border rounded">
    <div class="grid grid-cols-1 md:grid-cols-6 gap-2">
      <div>
        <label class="block text-xs text-gray-600">{{ __('ui.common.status') }}</label>
        <select name="status" class="form-select w-full">
          <option value="">-- {{ __('ui.misc.all_statuses') }} --</option>
          @foreach($statuses as $st)
            <option value="{{ $st }}" @selected(request('status')===$st)>{{ __('ui.replenishments.status.'.strtolower($st)) }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-xs text-gray-600">{{ __('ui.common.warehouse') }}</label>
        <select name="warehouse_id" class="form-select w-full">
          <option value="">-- {{ __('ui.common.unassigned') }} --</option>
          @foreach($warehouses as $w)
            <option value="{{ $w->id }}" @selected(request('warehouse_id')===$w->id)>{{ $w->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-xs text-gray-600">{{ __('ui.common.franchisee') }}</label>
        <select name="franchisee_id" class="form-select w-full">
          <option value="">-- {{ __('ui.misc.all_franchisees') }} --</option>
          @foreach($franchisees as $f)
            <option value="{{ $f->id }}" @selected(request('franchisee_id')===$f->id)>{{ $f->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-xs text-gray-600">{{ __('ui.from_date') }}</label>
        <input type="date" name="from_date" class="form-input w-full" value="{{ request('from_date') }}">
      </div>
      <div>
        <label class="block text-xs text-gray-600">{{ __('ui.to_date') }}</label>
        <input type="date" name="to_date" class="form-input w-full" value="{{ request('to_date') }}">
      </div>
      <div>
        <label class="block text-xs text-gray-600">{{ __('ui.replenishments.filters.reference') }}</label>
  <input type="text" name="q" class="form-input w-full" placeholder="{{ __('ui.replenishments.filters.reference_placeholder') }}" value="{{ request('q') }}">
      </div>
    </div>
    <div class="mt-3 flex items-center gap-2">
      <button class="btn btn-primary">{{ __('ui.actions.filter') }}</button>
      <a href="{{ route('bo.replenishments.index') }}" class="btn">{{ __('ui.actions.reset') }}</a>
    </div>
  </form>

  @if($orders->isEmpty())
    <p class="text-gray-500">{{ __('ui.replenishments.none') }}</p>
  @else
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left text-gray-600">
          <th class="p-2">{{ __('ui.replenishments.csv.reference') }}</th>
          <th class="p-2">{{ __('ui.common.warehouse') }}</th>
          <th class="p-2">{{ __('ui.common.franchisee') }}</th>
          <th class="p-2">{{ __('ui.common.status') }}</th>
          <th class="p-2">{{ __('ui.labels.total') }}</th>
          <th class="p-2">{{ __('ui.labels.ratio_8020') }}</th>
          <th class="p-2">{{ __('ui.common.date') }}</th>
          <th class="p-2"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $po)
        <tr class="border-t">
          <td class="p-2">{{ $po->reference ?? $po->id }}</td>
          <td class="p-2">{{ $po->warehouse->name ?? '-' }}</td>
          <td class="p-2">{{ $po->franchisee->name ?? '-' }}</td>
          <td class="p-2">{{ __('ui.replenishments.status.'.strtolower($po->status)) }}</td>
          @php $totalCents = $po->lines->sum(fn($l) => ($l->unit_price_cents ?? 0)); @endphp
          <td class="p-2">{{ number_format($totalCents/100, 2) }}€</td>
          <td class="p-2">{{ $po->corp_ratio_cached !== null ? number_format((float)$po->corp_ratio_cached, 2).'%' : '-' }}</td>
          <td class="p-2">{{ $po->created_at->format('Y-m-d') }}</td>
          <td class="p-2 text-right">
            <a class="text-blue-600 hover:underline" href="{{ route('bo.replenishments.show', $po->id) }}">{{ __('ui.common.view') }}</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $orders->links() }}</div>
  @endif
</div>
@endsection
