@extends('layouts.app')

@section('content')
    <h1 class="page-title mb-6">My Dashboard</h1>

    <!-- Indicateurs du franchisé -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">My Trucks</h2>
            <p class="text-2xl font-semibold">{{ $truckCount }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">My Warehouses</h2>
            <p class="text-2xl font-semibold">{{ $warehouseCount }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">Total Stock Orders</h2>
            <p class="text-2xl font-semibold">{{ $totalOrders }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">Pending Stock Orders</h2>
            <p class="text-2xl font-semibold">{{ $pendingOrders }}</p>
        </div></div>
    </div>

    <!-- (Optionnel) Graphique ou liste d'activité -->
    <div class="mt-8 card">
        <div class="card-header"><h2 class="text-xl font-bold">Recent Activity</h2></div>
        <div class="card-body">
        <ul class="list-disc list-inside text-sm text-gray-700">
            <li>Latest stock order placed on ... (status ...)</li>
            <li>New delivery expected on ...</li>
            <!-- On pourrait afficher dynamiquement des événements récents liés à la franchise -->
        </ul>
        </div>
    </div>
@endsection
