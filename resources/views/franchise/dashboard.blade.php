@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Mon Tableau de Bord</h1>

    <!-- Indicateurs du franchisé -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">My Trucks</h2>
            <p class="text-2xl font-semibold">{{ $truckCount }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">My Warehouses</h2>
            <p class="text-2xl font-semibold">{{ $warehouseCount }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">Total Stock Orders</h2>
            <p class="text-2xl font-semibold">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">Pending Stock Orders</h2>
            <p class="text-2xl font-semibold">{{ $pendingOrders }}</p>
        </div>
    </div>

    <!-- (Optionnel) Graphique ou liste d'activité -->
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Recent Activity</h2>
        <ul class="list-disc list-inside text-sm text-gray-700">
            <li>Latest stock order placed on ... (status ...)</li>
            <li>New delivery expected on ...</li>
            <!-- On pourrait afficher dynamiquement des événements récents liés à la franchise -->
        </ul>
    </div>
@endsection
