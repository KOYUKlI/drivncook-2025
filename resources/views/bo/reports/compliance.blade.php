@extends('layouts.app-shell')

@section('sidebar')
  @include('layouts.partials.sidebar')
@endsection

@section('content')
  <x-ui.breadcrumbs :items="[
    ['title' => __('ui.titles.reports') ?? __('ui.nav.reports') ?? 'Reports', 'url' => route('bo.reports.monthly')],
    ['title' => __('ui.titles.reports_compliance') ?? __('ui.nav.reports_compliance') ?? 'Compliance Report'],
  ]" />

  <div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
      <div class="sm:flex-auto">
  <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.titles.reports_compliance') ?? __('ui.nav.reports_compliance') ?? 'Compliance Report' }}</h1>
  <p class="text-gray-600">{{ __('ui.help.compliance_threshold') ?? 'Target ratio' }} ≥ 80%</p>
      </div>
      @can('viewAny', App\Models\PurchaseOrder::class)
      <a href="{{ route('bo.reports.compliance', array_filter(array_merge(request()->query(), ['export' => 1]))) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        {{ __('ui.actions.export_csv') ?? 'Exporter en CSV' }}
      </a>
      @endcan
    </div>
  </div>

  <!-- Filters -->
  <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
  <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.reports.monthly_sales.filters.title') ?? __('ui.fo.sales.filters.title') ?? 'Filters' }}</h3>
      <a href="{{ route('bo.reports.compliance') }}" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
        {{ __('ui.actions.reset') }}
      </a>
    </div>
    <div class="p-6">
      <form method="get" action="{{ route('bo.reports.compliance') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label for="from_date" class="block text-sm font-medium text-gray-700">{{ __('ui.labels.from_date') ?? 'From date' }}</label>
          <input id="from_date" name="from_date" type="date" value="{{ $filters['from_date'] ?? '' }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
        </div>
        <div>
          <label for="to_date" class="block text-sm font-medium text-gray-700">{{ __('ui.labels.to_date') ?? 'To date' }}</label>
          <input id="to_date" name="to_date" type="date" value="{{ $filters['to_date'] ?? '' }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
        </div>
        <div>
          <label for="franchisee_id" class="block text-sm font-medium text-gray-700">{{ __('ui.labels.franchisee') ?? 'Franchisee' }}</label>
          <select id="franchisee_id" name="franchisee_id" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">{{ __('ui.bo.reports.monthly_sales.filters.all_franchisees') ?? __('ui.misc.all_franchisees') ?? 'All Franchisees' }}</option>
            @foreach($franchisees as $f)
              <option value="{{ $f->id }}" @selected(($filters['franchisee_id'] ?? '') == $f->id)>{{ $f->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex items-end">
          <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('ui.actions.filter') ?? 'Filter' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
  <div class="text-sm text-gray-500">{{ __('ui.bo.reports.compliance.stats.total_orders') ?? 'Total Orders' }}</div>
      <div class="text-2xl font-semibold">{{ $metrics['total_count'] ?? 0 }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
  <div class="text-sm text-gray-500">{{ __('ui.bo.reports.compliance.stats.compliant_orders') ?? 'Compliant Orders' }}</div>
      <div class="text-2xl font-semibold">{{ $metrics['compliant_count'] ?? 0 }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
  <div class="text-sm text-gray-500">{{ __('ui.bo.reports.compliance.stats.non_compliant_orders') ?? 'Non-Compliant Orders' }}</div>
      <div class="text-2xl font-semibold">{{ $metrics['non_compliant_count'] ?? 0 }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
  <div class="text-sm text-gray-500">{{ __('ui.bo.reports.compliance.stats.compliance_rate') ?? 'Compliance Rate' }}</div>
      <div class="text-2xl font-semibold">{{ number_format((float)($metrics['compliance_rate'] ?? 0), 1) }}%</div>
    </div>
  </div>

  <!-- Orders table -->
  <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
    @if(($orders->count() ?? 0) === 0)
      <div class="p-8 text-center text-gray-500">
        <div class="text-lg font-medium mb-1">{{ __('ui.empty.no_data') }}</div>
        <div class="text-sm">{{ __('ui.empty.try_changing_filters') }}</div>
      </div>
    @else
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">{{ __('ui.titles.reports_compliance') }}</h3>
      </div>
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.date') ?? 'Date' }}</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.franchisee') ?? 'Franchisee' }}</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.total') ?? 'Total' }}</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.ratio_8020') ?? '80/20 Ratio' }}</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.status') ?? 'Status' }}</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach($orders as $po)
            @php
              $total = (int) ($po->computed_total_cents ?? 0);
              $ratio = (float) ($po->corp_ratio_cached ?? $po->computed_ratio ?? 0);
              $ok = $ratio >= 80;
            @endphp
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-3 text-sm text-gray-700">{{ optional($po->created_at)->toDateString() }}</td>
              <td class="px-6 py-3 text-sm text-gray-700">{{ $po->franchisee->name ?? '-' }}</td>
              <td class="px-6 py-3 text-sm text-gray-900 text-right">{{ number_format($total / 100, 2, ',', ' ') }} €</td>
              <td class="px-6 py-3 text-sm text-right">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $ok ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                  {{ number_format($ratio, 2, ',', ' ') }}%
                </span>
              </td>
              <td class="px-6 py-3 text-sm text-right text-gray-700">
                {{ $ok ? __('ui.misc.compliant') ?? 'Compliant' : __('ui.misc.non_compliant') ?? 'Non-Compliant' }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  @if(!empty($byFranchisee))
  <!-- By franchisee summary -->
  <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
  <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.purchase_orders.compliance_report.by_franchisee.title') ?? 'By franchisee' }}</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.franchisee') ?? 'Franchisee' }}</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.orders') ?? 'Orders' }}</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.avg_ratio') ?? 'Average ratio' }}</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.bo.reports.compliance.stats.compliance_rate') ?? 'Compliance Rate' }}</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
      @foreach($byFranchisee as $row)
            <tr class="hover:bg-gray-50">
        <td class="px-6 py-3 text-sm text-gray-700">{{ $row['name'] ?? '-' }}</td>
        <td class="px-6 py-3 text-sm text-right text-gray-700">{{ $row['orders_count'] ?? 0 }}</td>
              <td class="px-6 py-3 text-sm text-right text-gray-700">{{ number_format((float)($row['avg_ratio'] ?? 0), 2, ',', ' ') }}%</td>
              <td class="px-6 py-3 text-sm text-right text-gray-700">{{ number_format((float)($row['compliance_rate'] ?? 0), 1, ',', ' ') }}%</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif
@endsection
