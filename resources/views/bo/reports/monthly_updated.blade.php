@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.sidebar.reports'), 'url' => '#'],
        ['title' => __('ui.sidebar.monthly_reports')]
    ]" />

    <div class="mb-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.reports.monthly.title') }}</h1>
                <p class="text-gray-600">{{ __('ui.bo.reports.monthly.subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @foreach (['success' => 'green', 'warning' => 'yellow', 'error' => 'red'] as $type => $color)
        @if (session($type))
            <div class="mb-4 rounded-md bg-{{ $color }}-50 p-4 border border-{{ $color }}-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        @if($type === 'success')
                            <svg class="h-5 w-5 text-{{ $color }}-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        @elseif($type === 'warning')
                            <svg class="h-5 w-5 text-{{ $color }}-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="h-5 w-5 text-{{ $color }}-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-{{ $color }}-800">
                            {{ session($type) }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Filters Section -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.reports.monthly.filters.title') }}</h3>
            <a href="{{ route('bo.reports.monthly') }}" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                <svg class="-ml-1 mr-1 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('ui.bo.reports.monthly.filters.reset') }}
            </a>
        </div>
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 gap-6 md:grid-cols-4">
                <!-- Year Filter -->
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">
                        {{ __('ui.bo.reports.monthly.filters.year') }}
                    </label>
                    <select name="year" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @php($years = (isset($availableYears) && $availableYears->count()) ? $availableYears : collect(range(now()->year, now()->year - 3)))
                        @foreach($years as $yearOption)
                            <option value="{{ $yearOption }}" {{ (int)$year == (int)$yearOption ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Month Filter -->
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">
                        {{ __('ui.bo.reports.monthly.filters.month') }}
                    </label>
                    <select name="month" id="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('ui.bo.reports.monthly.filters.all_months') }}</option>
                        @foreach(range(1, 12) as $monthOption)
                            <option value="{{ $monthOption }}" {{ $month == $monthOption ? 'selected' : '' }}>
                                {{ __('ui.months.' . $monthOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Franchisee Filter -->
                <div>
                    <label for="franchisee_id" class="block text-sm font-medium text-gray-700">
                        {{ __('ui.bo.reports.monthly.filters.franchisee') }}
                    </label>
                    <select name="franchisee_id" id="franchisee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('ui.bo.reports.monthly.filters.all_franchisees') }}</option>
                        @foreach($franchisees as $franchisee)
                            <option value="{{ $franchisee->id }}" {{ $franchiseeId == $franchisee->id ? 'selected' : '' }}>
                                {{ $franchisee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        {{ __('ui.bo.reports.monthly.filters.apply') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Generate Report Section -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.reports.monthly.generate.title') }}</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('bo.reports.monthly.generate') }}" class="grid grid-cols-1 gap-6 md:grid-cols-4">
                @csrf
                
                <!-- Year -->
                <div>
                    <label for="gen_year" class="block text-sm font-medium text-gray-700">
                        {{ __('ui.bo.reports.monthly.generate.year') }}
                    </label>
                    <select name="year" id="gen_year" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach(range(now()->year, now()->year - 2) as $yearOption)
                            <option value="{{ $yearOption }}" {{ $yearOption == now()->year ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Month -->
                <div>
                    <label for="gen_month" class="block text-sm font-medium text-gray-700">
                        {{ __('ui.bo.reports.monthly.generate.month') }}
                    </label>
                    <select name="month" id="gen_month" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach(range(1, 12) as $monthOption)
                            <option value="{{ $monthOption }}" {{ $monthOption == now()->month ? 'selected' : '' }}>
                                {{ __('ui.months.' . $monthOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Franchisee -->
                <div>
                    <label for="gen_franchisee_id" class="block text-sm font-medium text-gray-700">
                        {{ __('ui.bo.reports.monthly.generate.franchisee') }}
                    </label>
                    <select name="franchisee_id" id="gen_franchisee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('ui.bo.reports.monthly.generate.all_franchisees') }}</option>
                        @foreach($franchisees as $franchisee)
                            <option value="{{ $franchisee->id }}">{{ $franchisee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Generate Button -->
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('ui.bo.reports.monthly.generate.button') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.reports.monthly.table.title') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.reports.monthly.table.franchisee') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.reports.monthly.table.period') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.reports.monthly.table.generated_at') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.reports.monthly.table.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $report->franchisee?->name ?? __('ui.bo.reports.monthly.table.all_franchisees') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-900">
                                {{ __('ui.months.' . $report->month) }} {{ $report->year }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $report->generated_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('bo.reports.download', $report->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-0.5 mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                {{ __('ui.bo.reports.monthly.table.download') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-gray-500 flex flex-col items-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('ui.bo.reports.monthly.empty.title') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('ui.bo.reports.monthly.empty.description') }}</p>
                                <div class="mt-6">
                                    <button type="button" onclick="document.getElementById('gen_year').focus()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        {{ __('ui.bo.reports.monthly.empty.generate_first') }}
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
