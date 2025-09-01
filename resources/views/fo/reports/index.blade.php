@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.reports.title'))

@section('content')
<div class="container py-6">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ __('ui.fo.reports.title') }}</h1>
    </div>

    @if (session('error'))
        <div class="alert alert-error shadow-lg mb-6">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title">{{ __('ui.fo.reports.filters.title') }}</h2>
            <form action="{{ route('fo.reports.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="form-control">
                    <label class="label">{{ __('ui.fo.reports.filters.year') }}</label>
                    <select name="year" class="select select-bordered w-full max-w-xs">
                        <option value="">{{ __('ui.fo.reports.filters.all_years') }}</option>
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label">{{ __('ui.fo.reports.filters.month') }}</label>
                    <select name="month" class="select select-bordered w-full max-w-xs">
                        <option value="">{{ __('ui.fo.reports.filters.all_months') }}</option>
                        @foreach ($months as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ __('ui.months.' . $m) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control mt-auto">
                    <button type="submit" class="btn btn-primary">{{ __('ui.fo.reports.filters.apply') }}</button>
                </div>
                <div class="form-control mt-auto">
                    <a href="{{ route('fo.reports.index') }}" class="btn btn-ghost">{{ __('ui.fo.reports.filters.reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">{{ __('ui.fo.reports.table.title') }}</h2>
            <div class="overflow-x-auto">
                @if ($reports->count() > 0)
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>{{ __('ui.fo.reports.table.period') }}</th>
                                <th>{{ __('ui.fo.reports.table.type') }}</th>
                                <th>{{ __('ui.fo.reports.table.generated_at') }}</th>
                                <th>{{ __('ui.fo.reports.table.file_size') }}</th>
                                <th class="text-right">{{ __('ui.fo.reports.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    <td>{{ __('ui.months.' . $report->month) }} {{ $report->year }}</td>
                                    <td>{{ __('ui.fo.reports.types.' . $report->type) }}</td>
                                    <td>{{ $report->generated_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if (Storage::disk('public')->exists($report->storage_path))
                                            {{ number_format(Storage::disk('public')->size($report->storage_path) / 1024, 0) }} KB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('fo.reports.download', $report->id) }}" class="btn btn-sm btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            {{ __('ui.fo.reports.table.download') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h3 class="font-bold">{{ __('ui.fo.reports.empty.title') }}</h3>
                                <div class="text-sm">{{ __('ui.fo.reports.empty.description') }}</div>
                                <div class="text-xs mt-2">{{ __('ui.fo.reports.empty.help') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
