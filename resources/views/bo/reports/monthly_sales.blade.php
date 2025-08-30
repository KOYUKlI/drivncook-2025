@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.bo.reports.monthly_sales.title')]
    ]" />

    <div class="mb-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.reports.monthly_sales.title') }}</h1>
                <p class="text-gray-600">{{ __('ui.bo.reports.monthly_sales.subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Filters & Generate Section -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.reports.monthly_sales.filters.title') }}</h3>
        </div>
        <div class="p-6">
            <form method="GET" class="space-y-4 lg:flex lg:items-end lg:space-y-0 lg:space-x-4">
                <!-- Year Filter -->
                <div class="flex-1">
                    <label for="year" class="block text-sm font-medium text-gray-700">
                        {{ __('ui.bo.reports.monthly_sales.filters.year') }}
                    </label>
                    <select name="year" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach(range(now()->year, now()->year - 3) as $yearOption)
                            <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Month Filter -->
                <div class="flex-1">
                    <label for="month" class="block text-sm font-medium text-gray-700">
                        {{ __('ui.bo.reports.monthly_sales.filters.month') }}
                    </label>
                    <select name="month" id="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('ui.bo.reports.monthly_sales.filters.all_months') }}</option>
                        @foreach(range(1, 12) as $monthOption)
                            <option value="{{ $monthOption }}" {{ $month == $monthOption ? 'selected' : '' }}>
                                {{ __('ui.months.' . $monthOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Franchisee Filter -->
                <div class="flex-1">
                    <label for="franchisee_id" class="block text-sm font-medium text-gray-700">
                        {{ __('ui.bo.reports.monthly_sales.filters.franchisee') }}
                    </label>
                    <select name="franchisee_id" id="franchisee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('ui.bo.reports.monthly_sales.filters.all_franchisees') }}</option>
                        @foreach($franchisees as $franchisee)
                            <option value="{{ $franchisee->id }}" {{ $franchiseeId == $franchisee->id ? 'selected' : '' }}>
                                {{ $franchisee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('ui.bo.reports.monthly_sales.filters.apply') }}
                    </button>
                    <a href="{{ route('bo.reports.monthly') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('ui.bo.reports.monthly_sales.filters.reset') }}
                    </a>
                </div>
            </form>

            <!-- Generate New Report Form -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-900 mb-4">{{ __('ui.bo.reports.monthly_sales.generate.title') }}</h4>
                <form method="POST" action="{{ route('bo.reports.monthly.generate') }}" class="space-y-4 lg:flex lg:items-end lg:space-y-0 lg:space-x-4">
                    @csrf
                    
                    <!-- Year -->
                    <div class="flex-1">
                        <label for="gen_year" class="block text-sm font-medium text-gray-700">
                            {{ __('ui.bo.reports.monthly_sales.generate.year') }}
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
                    <div class="flex-1">
                        <label for="gen_month" class="block text-sm font-medium text-gray-700">
                            {{ __('ui.bo.reports.monthly_sales.generate.month') }}
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
                    <div class="flex-1">
                        <label for="gen_franchisee_id" class="block text-sm font-medium text-gray-700">
                            {{ __('ui.bo.reports.monthly_sales.generate.franchisee') }}
                        </label>
                        <select name="franchisee_id" id="gen_franchisee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('ui.bo.reports.monthly_sales.generate.all_franchisees') }}</option>
                            @foreach($franchisees as $franchisee)
                                <option value="{{ $franchisee->id }}">{{ $franchisee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Generate Button -->
                    <div>
                        <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('ui.bo.reports.monthly_sales.generate.button') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.reports.monthly_sales.table.title') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.reports.monthly_sales.table.franchisee') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.reports.monthly_sales.table.period') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.reports.monthly_sales.table.generated_at') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.reports.monthly_sales.table.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $report->franchisee?->name ?? __('ui.bo.reports.monthly_sales.table.all_franchisees') }}
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
                               class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M7 7h.01M17 7h.01M7 17h.01M17 17h.01"></path>
                                </svg>
                                {{ __('ui.bo.reports.monthly_sales.table.download') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m-16-4c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('ui.bo.reports.monthly_sales.empty.title') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('ui.bo.reports.monthly_sales.empty.description') }}</p>
                                <div class="mt-6">
                                    <button type="button" onclick="document.getElementById('gen_year').focus()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        {{ __('ui.bo.reports.monthly_sales.empty.generate_first') }}
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
