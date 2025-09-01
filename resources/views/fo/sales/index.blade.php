@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.sales.index.title'))

@section('content')
<div class="container py-6">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ __('ui.fo.sales.index.title') }}</h1>
        <a href="{{ route('fo.sales.create') }}" class="btn btn-primary">
            {{ __('ui.fo.sales.index.create_new') }}
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title">{{ __('ui.fo.sales.index.filter') }}</h2>
            <form action="{{ route('fo.sales.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="form-control">
                    <label class="label">{{ __('ui.fo.sales.index.from_date') }}</label>
                    <input type="date" name="from_date" value="{{ $fromDate->format('Y-m-d') }}" class="input input-bordered w-full max-w-xs" />
                </div>
                <div class="form-control">
                    <label class="label">{{ __('ui.fo.sales.index.to_date') }}</label>
                    <input type="date" name="to_date" value="{{ $toDate->format('Y-m-d') }}" class="input input-bordered w-full max-w-xs" />
                </div>
                <div class="form-control mt-auto">
                    <button type="submit" class="btn btn-primary">{{ __('ui.fo.sales.index.filter_submit') }}</button>
                </div>
                <div class="form-control mt-auto">
                    <a href="{{ route('fo.sales.index') }}" class="btn btn-ghost">{{ __('ui.fo.sales.index.reset') }}</a>
                </div>
                <div class="form-control mt-auto ml-auto">
                    <button type="submit" name="export" value="csv" class="btn btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('ui.fo.sales.index.export_csv') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="stats shadow mb-6 w-full">
        <div class="stat">
            <div class="stat-title">{{ __('ui.fo.sales.index.period') }}</div>
            <div class="stat-value">{{ $fromDate->format('d/m/Y') }} - {{ $toDate->format('d/m/Y') }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">{{ __('ui.fo.sales.index.total_sales') }}</div>
            <div class="stat-value">{{ $totalSales }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">{{ __('ui.fo.sales.index.total_amount') }}</div>
            <div class="stat-value">{{ number_format($totalAmount, 2) }} €</div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>{{ __('ui.fo.sales.index.table.date') }}</th>
                    <th>{{ __('ui.fo.sales.index.table.items') }}</th>
                    <th class="text-right">{{ __('ui.fo.sales.index.table.total') }}</th>
                    <th class="text-right">{{ __('ui.fo.sales.index.table.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_date->format('d/m/Y') }}</td>
                    <td>{{ $sale->lines()->count() }}</td>
                    <td class="text-right">{{ number_format($sale->total_cents / 100, 2) }} €</td>
                    <td class="text-right">
                        <a href="{{ route('fo.sales.show', $sale->id) }}" class="btn btn-sm btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">{{ __('ui.fo.sales.index.no_sales_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>
@endsection
