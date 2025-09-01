@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.titles.purchase_orders'))

@section('content')
<div class="py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.titles.purchase_orders') }}</h1>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
        <div class="p-4 md:p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">{{ __('ui.actions.filter') }}</h2>
            <form action="{{ route('fo.replenishments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">{{ __('ui.labels.from_date') }}</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-input w-full" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">{{ __('ui.labels.to_date') }}</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-input w-full" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">{{ __('ui.replenishments.filters.reference') }}</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('ui.replenishments.filters.reference_placeholder') }}" class="form-input w-full" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">{{ __('ui.labels.status') }}</label>
                    <select name="status" class="form-select w-full">
                        <option value="">{{ __('ui.misc.all_statuses') }}</option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status')===$s)>{{ __('ui.replenishments.status.' . strtolower($s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex gap-2 justify-end">
                    <button type="submit" class="btn-primary">{{ __('ui.actions.filter') }}</button>
                    <a href="{{ route('fo.replenishments.index') }}" class="btn-secondary">{{ __('ui.actions.reset') }}</a>
                    <button type="submit" name="export" value="csv" class="btn-secondary">{{ __('ui.audit.export_csv') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.replenishments.csv.reference') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.status') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.created_at') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.shipped_at') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.delivered_at') }}</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.total') }}</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($orders as $po)
                <tr>
                    <td class="px-4 py-2">
                        <a href="{{ route('fo.replenishments.show', $po->id) }}" class="text-orange-600 hover:text-orange-800 font-medium">{{ $po->reference ?? $po->id }}</a>
                    </td>
                    <td class="px-4 py-2">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            @class([
                                'bg-gray-100 text-gray-800' => strtolower($po->status) === 'draft',
                                'bg-blue-100 text-blue-800' => strtolower($po->status) === 'approved',
                                'bg-yellow-100 text-yellow-800' => strtolower($po->status) === 'picked',
                                'bg-purple-100 text-purple-800' => strtolower($po->status) === 'shipped',
                                'bg-green-100 text-green-800' => strtolower($po->status) === 'delivered' || strtolower($po->status) === 'closed',
                                'bg-red-100 text-red-800' => strtolower($po->status) === 'cancelled',
                            ])">
                            {{ __('ui.replenishments.status.' . strtolower($po->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ optional($po->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2">{{ optional($po->shipped_at)->format('d/m/Y H:i') ?: '—' }}</td>
                    <td class="px-4 py-2">{{ optional($po->delivered_at)->format('d/m/Y H:i') ?: '—' }}</td>
                    <td class="px-4 py-2 text-right">
                        @php $totalCents = $po->lines->sum(fn($l) => ($l->unit_price_cents ?? 0)); @endphp
                        {{ number_format($totalCents / 100, 2) }} €
                    </td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('fo.replenishments.show', $po->id) }}" class="text-orange-600 hover:text-orange-800 inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('ui.empty.no_purchase_orders') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
