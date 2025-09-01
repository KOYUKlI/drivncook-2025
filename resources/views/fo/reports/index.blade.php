@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.reports.title'))

@section('content')
<div class="py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.fo.reports.title') }}</h1>
    </div>

    @if (session('error'))
        <div class="mb-6 rounded-md border border-red-300 bg-red-50 text-red-800 p-3 text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
        <div class="p-4 md:p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">{{ __('ui.fo.reports.filters.title') }}</h2>
            <form action="{{ route('fo.reports.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">{{ __('ui.fo.reports.filters.year') }}</label>
                    <select name="year" class="form-select w-40">
                        <option value="">{{ __('ui.fo.reports.filters.all_years') }}</option>
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">{{ __('ui.fo.reports.filters.month') }}</label>
                    <select name="month" class="form-select w-40">
                        <option value="">{{ __('ui.fo.reports.filters.all_months') }}</option>
                        @foreach ($months as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ __('ui.months.' . $m) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ml-auto flex gap-2">
                    <button type="submit" class="btn-primary">{{ __('ui.fo.reports.filters.apply') }}</button>
                    <a href="{{ route('fo.reports.index') }}" class="btn-secondary">{{ __('ui.fo.reports.filters.reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-4 md:p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">{{ __('ui.fo.reports.table.title') }}</h2>
            <div class="overflow-x-auto">
                @if ($reports->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.reports.table.period') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.reports.table.type') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.reports.table.generated_at') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.reports.table.file_size') }}</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.reports.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($reports as $report)
                                <tr>
                                    <td class="px-4 py-2">{{ __('ui.months.' . $report->month) }} {{ $report->year }}</td>
                                    <td class="px-4 py-2">{{ __('ui.fo.reports.types.' . $report->type) }}</td>
                                    <td class="px-4 py-2">{{ $report->generated_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-2">
                                        @if (Storage::disk('public')->exists($report->storage_path))
                                            {{ number_format(Storage::disk('public')->size($report->storage_path) / 1024, 0) }} KB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <a href="{{ route('fo.reports.download', $report->id) }}" class="btn-primary inline-flex items-center">
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
                    <div class="rounded-md border border-blue-300 bg-blue-50 text-blue-800 p-3 text-sm">
                        <div class="font-semibold">{{ __('ui.fo.reports.empty.title') }}</div>
                        <div>{{ __('ui.fo.reports.empty.description') }}</div>
                        <div class="text-xs mt-2">{{ __('ui.fo.reports.empty.help') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
