@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
  <x-ui.breadcrumbs :items="[
      ['title' => __('ui.titles.reports'), 'url' => route('bo.reports.monthly')],
      ['title' => __('ui.nav.reports'), 'url' => route('bo.reports.monthly')],
      ['title' => __('audit.title')],
  ]" />

  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">{{ __('audit.title') }}</h1>
      <p class="mt-1 text-sm text-gray-500">{{ __('audit.subtitle') }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex">
      <div class="inline-flex rounded-md shadow-sm" role="group">
        <a href="{{ route('bo.audit.index', array_merge(request()->query(), ['export' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
          </svg>
          {{ __('audit.export_csv') }}
        </a>
        <a href="{{ route('bo.audit.index', array_merge(request()->query(), ['export' => 'xlsx'])) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-r border-gray-300 rounded-r-lg hover:bg-gray-50">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          {{ __('audit.export_xlsx') }}
        </a>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-lg shadow mb-6" x-data="{ openFilters: false }">
    <div class="p-4 border-b border-gray-200">
      <button type="button" class="flex items-center justify-between w-full" @click="openFilters = !openFilters">
        <span class="text-base font-medium text-gray-700">{{ __('audit.filters') }}</span>
        <svg class="w-5 h-5 text-gray-500" :class="{'transform rotate-180': openFilters}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
      </button>
    </div>

    <div x-show="openFilters" x-transition class="p-4 border-b border-gray-200">
      <form method="get" action="{{ route('bo.audit.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label for="period" class="block text-sm font-medium text-gray-700">{{ __('audit.period') }}</label>
            <select id="period" name="period" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
              <option value="custom" selected>{{ __('audit.custom_period') }}</option>
              <option value="24h">{{ __('audit.last_24h') }}</option>
              <option value="7d">{{ __('audit.last_7d') }}</option>
              <option value="30d">{{ __('audit.last_30d') }}</option>
            </select>
          </div>

          <div class="col-span-1 sm:col-span-2 lg:col-span-1">
            <label for="from_date" class="block text-sm font-medium text-gray-700">{{ __('ui.labels.from_date') }}</label>
            <input type="date" id="from_date" name="from_date" value="{{ $filters['from_date'] ?? '' }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
          </div>

          <div class="col-span-1 sm:col-span-2 lg:col-span-1">
            <label for="to_date" class="block text-sm font-medium text-gray-700">{{ __('ui.labels.to_date') }}</label>
            <input type="date" id="to_date" name="to_date" value="{{ $filters['to_date'] ?? '' }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
          </div>

          <div class="col-span-1 sm:col-span-2 lg:col-span-1">
            <label for="user_id" class="block text-sm font-medium text-gray-700">{{ __('audit.user') }}</label>
            <select id="user_id" name="user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
              <option value="">{{ __('audit.all_users') }}</option>
              @foreach($users as $u)
                <option value="{{ $u->id }}" @selected(($filters['user_id'] ?? '') == $u->id)>{{ $u->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-span-1 sm:col-span-2 lg:col-span-3">
            <label for="route" class="block text-sm font-medium text-gray-700">{{ __('audit.route') }}</label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
              </div>
              <input type="text" name="route" id="route" value="{{ $filters['route'] ?? '' }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="{{ __('audit.search_placeholder') }}">
            </div>
          </div>
        </div>

        <div class="flex justify-end space-x-3">
          <a href="{{ route('bo.audit.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('audit.reset_filters') }}
          </a>
          <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('audit.apply_filters') }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="bg-white rounded-lg shadow overflow-hidden">
    @if($logs->count() === 0)
      <div class="p-8 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">{{ __('audit.no_logs') }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ __('audit.no_logs_desc') }}</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('audit.timestamp') }}</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('audit.user') }}</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('audit.method') }}</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('audit.route') }}</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('audit.resource') }}</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('audit.ip') }}</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('audit.details') }}</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($logs as $log)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <span class="font-medium">{{ optional($log->created_at)->format('d/m/Y') }}</span>
                  <span class="text-gray-400">{{ optional($log->created_at)->format('H:i:s') }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @if($log->user)
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-indigo-800">{{ substr(optional($log->user)->name, 0, 2) }}</span>
                      </div>
                      <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">{{ optional($log->user)->name }}</div>
                      </div>
                    </div>
                  @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ __('ui.misc.not_provided') }}</span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @php
                    $methodClass = 'bg-gray-100 text-gray-800';
                    if ($log->method === 'GET') $methodClass = 'bg-blue-100 text-blue-800';
                    if ($log->method === 'POST') $methodClass = 'bg-green-100 text-green-800';
                    if ($log->method === 'PUT' || $log->method === 'PATCH') $methodClass = 'bg-yellow-100 text-yellow-800';
                    if ($log->method === 'DELETE') $methodClass = 'bg-red-100 text-red-800';
                  @endphp
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $methodClass }}">
                    {{ $log->method }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <span class="max-w-xs truncate block">{{ $log->route }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  @if($log->subject_type)
                    <span class="text-xs">{{ Str::afterLast($log->subject_type, '\\') }}</span>
                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100">#{{ $log->subject_id }}</span>
                  @else
                    <span class="text-gray-400">-</span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $log->ip }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                  <button type="button" class="text-indigo-600 hover:text-indigo-900" title="{{ $log->user_agent }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $logs->links() }}
      </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const periodSelect = document.getElementById('period');
    const fromDateInput = document.getElementById('from_date');
    const toDateInput = document.getElementById('to_date');
    
    periodSelect.addEventListener('change', function() {
      const today = new Date();
      let fromDate = new Date();
      
      switch(this.value) {
        case '24h':
          fromDate.setDate(today.getDate() - 1);
          break;
        case '7d':
          fromDate.setDate(today.getDate() - 7);
          break;
        case '30d':
          fromDate.setDate(today.getDate() - 30);
          break;
        default:
          // For custom, don't change the dates
          return;
      }
      
      fromDateInput.value = fromDate.toISOString().split('T')[0];
      toDateInput.value = today.toISOString().split('T')[0];
    });
  });
</script>
@endpush
@endsection
