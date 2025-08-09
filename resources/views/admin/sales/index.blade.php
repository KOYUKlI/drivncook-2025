@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
        <h1 class="page-title">Sales</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.exports.sales.pdf', ['download' => 1]) }}" class="btn-secondary">Download PDF</a>
            <button type="button" class="btn-primary" x-data x-on:click="$dispatch('open-modal', 'preview-sales-pdf')">Preview PDF</button>
        </div>
    </div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Truck</th>
                    <th>Franchise</th>
                    <th>Ordered At</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ optional($order->truck)->name }}</td>
                        <td>{{ optional(optional($order->truck)->franchise)->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') }}</td>
                        <td class="text-center"><a class="btn-link" href="{{ route('admin.sales.show', $order) }}">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-3 text-gray-500">No sales yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('modals')
    <x-modal name="preview-sales-pdf" maxWidth="2xl">
        <div class="p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold">Sales PDF Preview</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.exports.sales.pdf', ['download' => 1]) }}" class="btn-secondary">Download</a>
                    <button class="btn-outline" x-on:click="$dispatch('close-modal', 'preview-sales-pdf')">Close</button>
                </div>
            </div>
            <div class="border rounded overflow-hidden" style="height: 75vh;">
                <iframe src="{{ route('admin.exports.sales.pdf') }}" class="w-full h-full" title="Sales PDF"></iframe>
            </div>
        </div>
    </x-modal>
@endpush
