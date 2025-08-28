@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.franchisees'), 'url' => route('bo.franchisees.index')],
        ['title' => $franchisee->name]
    ]" />

    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $franchisee->name }}</h1>
                <p class="text-gray-600">{{ $franchisee->contact_email }} • {{ $franchisee->territory }}</p>
            </div>
            
            @php
            $statusColors = [
                'active' => 'bg-green-100 text-green-800',
                'inactive' => 'bg-gray-100 text-gray-800',
                'suspended' => 'bg-red-100 text-red-800'
            ];
            @endphp
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$franchisee->status] }}">
                {{ __('ui.' . $franchisee->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left column: Franchisee info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Franchisee Information -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.franchisee_information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.contact_name') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->contact_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.email') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->contact_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.phone') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->contact_phone ?? 'Non renseigné' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.territory') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->territory }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.contract_start') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->contract_start_date?->format('d/m/Y') ?? 'Non défini' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.trucks_assigned') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->trucks->count() }} camion(s)</dd>
                    </div>
                </div>
            </div>

            <!-- Assigned Trucks -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.assigned_trucks') }}</h3>
                @if($franchisee->trucks->count() > 0)
                    <div class="space-y-3">
                        @foreach($franchisee->trucks as $truck)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $truck->code }}</p>
                                    <p class="text-xs text-gray-500">{{ $truck->license_plate ?? 'Plaque non renseignée' }}</p>
                                </div>
                            </div>
                            @php
                            $truckStatusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'maintenance' => 'bg-orange-100 text-orange-800',
                                'inactive' => 'bg-gray-100 text-gray-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $truckStatusColors[$truck->status] }}">
                                {{ __('ui.' . $truck->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Aucun camion assigné à ce franchisé.</p>
                @endif
            </div>

            <!-- Recent Sales -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.recent_sales') }}</h3>
                @if($franchisee->sales && $franchisee->sales->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Articles</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($franchisee->sales->take(10) as $sale)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $sale->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($sale->total_cents / 100, 2, ',', ' ') }}€
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $sale->lines->count() }} article(s)
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Aucune vente récente pour ce franchisé.</p>
                @endif
            </div>
        </div>

        <!-- Right column: Stats and Reports -->
        <div>
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.performance_stats') }}</h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-sm font-medium text-gray-500">CA des 30 derniers jours</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format(($stats['total_revenue_30d'] ?? 0) / 100, 2, ',', ' ') }}€</div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-500">Nombre de ventes</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['sales_count_30d'] ?? 0 }}</div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-500">Transaction moyenne</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format(($stats['avg_transaction'] ?? 0) / 100, 2, ',', ' ') }}€</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
