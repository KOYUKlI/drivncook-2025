@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.page-header :title="__('ui.bo.fo_orders.index.title')">
    <x-slot:actions>
        <a href="{{ route('bo.fo-orders.export', request()->query()) }}" class="btn-secondary">{{ __('ui.audit.export_csv') }}</a>
    </x-slot:actions>
</x-ui.page-header>
<div class="max-w-6xl mx-auto">
    <x-ui.card>
        <table class="min-w-full">
            <thead>
                <tr class="text-left text-sm text-gray-500">
                    <th class="px-2 py-2">{{ __('ui.replenishments.csv.reference') }}</th>
                    <th class="px-2 py-2">{{ __('ui.labels.franchisee') }}</th>
                    <th class="px-2 py-2">{{ __('ui.labels.status') }}</th>
                    <th class="px-2 py-2">{{ __('ui.labels.total') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($orders as $o)
                <tr>
                    <td class="px-2 py-2"><a href="{{ route('bo.fo-orders.show', $o) }}" class="text-orange-700 hover:underline">{{ $o->reference }}</a></td>
                    <td class="px-2 py-2">{{ $o->franchisee->name ?? '-' }}</td>
                    <td class="px-2 py-2">{{ $o->status }}</td>
                    <td class="px-2 py-2">{{ number_format($o->total_cents/100, 2, app()->getLocale()==='fr'?',':'.') }} {{ __('ui.misc.euro') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-2 py-6 text-sm text-gray-500">{{ __('ui.empty.no_purchase_orders') }}</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $orders->links() }}</div>
    </x-ui.card>
</div>
@endsection
