@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('content')
<x-ui.page-header :title="__('ui.fo.orders_request.index.title')">
    <x-slot:actions>
        <a href="{{ route('fo.orders.new') }}" class="inline-flex items-center px-3 py-2 bg-orange-600 text-white rounded">{{ __('ui.fo.orders_request.actions.new') }}</a>
    </x-slot:actions>
</x-ui.page-header>
<div class="max-w-6xl mx-auto">
    <x-ui.card>
        <form method="GET" action="{{ route('fo.orders.index') }}" class="mb-4 grid grid-cols-12 gap-2 items-end">
            <div class="col-span-3">
                <label class="block text-xs text-gray-600">{{ __('ui.labels.status') }}</label>
                <select name="status" class="w-full border rounded px-2 py-1">
                    <option value="">{{ __('ui.misc.all_statuses') }}</option>
                    <option value="Draft" @selected(request('status')==='Draft')>Draft</option>
                    <option value="Submitted" @selected(request('status')==='Submitted')>Submitted</option>
                </select>
            </div>
            <div class="col-span-3">
                <label class="block text-xs text-gray-600">{{ __('ui.labels.from_date') }}</label>
                <input type="date" name="from" value="{{ request('from') }}" class="w-full border rounded px-2 py-1" />
            </div>
            <div class="col-span-3">
                <label class="block text-xs text-gray-600">{{ __('ui.labels.to_date') }}</label>
                <input type="date" name="to" value="{{ request('to') }}" class="w-full border rounded px-2 py-1" />
            </div>
            <div class="col-span-3 flex gap-2">
                <x-ui.button type="submit">{{ __('ui.actions.filter') }}</x-ui.button>
                <a href="{{ route('fo.orders.index') }}" class="px-3 py-2 border rounded text-sm">{{ __('ui.actions.reset') }}</a>
                <a href="{{ route('fo.orders.index', array_merge(request()->query(), ['export_csv'=>1])) }}" class="px-3 py-2 bg-gray-800 text-white rounded text-sm">{{ __('ui.fo.orders_request.export_csv') }}</a>
            </div>
        </form>
        <table class="min-w-full">
            <thead>
            <tr class="text-left text-sm text-gray-500">
                <th class="px-2 py-2">{{ __('ui.replenishments.csv.reference') }}</th>
                <th class="px-2 py-2">{{ __('ui.labels.status') }}</th>
                <th class="px-2 py-2">{{ __('ui.labels.total') }}</th>
                <th class="px-2 py-2">{{ __('ui.replenishments.csv.ratio8020') }}</th>
                <th class="px-2 py-2">{{ __('ui.common.actions') }}</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse($orders as $o)
                <tr>
                    <td class="px-2 py-2"><a class="text-orange-700 hover:underline" href="{{ route('fo.orders.show', $o) }}">{{ $o->reference }}</a></td>
                    <td class="px-2 py-2">{{ $o->status }}</td>
                    <td class="px-2 py-2">{{ number_format($o->total_cents/100, 2, app()->getLocale()==='fr'?',':'.') }} {{ __('ui.misc.euro') }}</td>
                    <td class="px-2 py-2">{{ number_format((float)$o->corp_ratio_cached, 2) }}%</td>
                    <td class="px-2 py-2">
                        @can('update', $o)
                            <a href="{{ route('fo.orders.edit', $o) }}" class="text-sm text-gray-700 hover:underline">{{ __('ui.edit') }}</a>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-2 py-6 text-sm text-gray-500">{{ __('ui.empty.no_purchase_orders') }}</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $orders->links() }}</div>
    </x-ui.card>
</div>
@endsection
