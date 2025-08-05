@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dashboard Administrateur</h1>

    <!-- Indicateurs clés -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">Total Trucks</h2>
            <p class="text-2xl font-semibold">{{ $truckCount }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">Total Warehouses</h2>
            <p class="text-2xl font-semibold">{{ $warehouseCount }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">Total Franchisees</h2>
            <p class="text-2xl font-semibold">{{ $franchiseCount }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">Total Sales (Count)</h2>
            <p class="text-2xl font-semibold">{{ $totalSalesCount }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">Total Sales (Revenue)</h2>
            <p class="text-2xl font-semibold">{{ number_format($totalSalesSum, 2) }} €</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-sm text-gray-500">Pending Stock Orders</h2>
            <p class="text-2xl font-semibold">{{ $pendingStockOrders }}</p>
        </div>
    </div>

    <!-- Graphique des ventes (exemple : ventes par mois) -->
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Sales Overview</h2>
        <canvas id="salesChart" width="400" height="200"></canvas>
    </div>

    <!-- (Optionnel) Tableau des dernières commandes de stock en attente -->
    @if(isset($latestPendingOrders))
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-2">Recent Pending Stock Orders</h2>
            <table class="min-w-full bg-white shadow rounded overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Order #</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Franchise</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @foreach($latestPendingOrders as $order)
                        <tr>
                            <td class="px-4 py-2">{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->truck->franchise->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-yellow-600 font-semibold">Pending</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@push('scripts')
<!-- Inclure Chart.js depuis un CDN ou via Vite -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Exemple : graphe en barres des ventes mensuelles (données fictives ou à calculer côté contrôleur)
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesMonthsLabels ?? []) !!},  // par ex. ['Jan', 'Fev', ...]
            datasets: [{
                label: 'Sales (€)',
                data: {!! json_encode($salesMonthsValues ?? []) !!}, // ex. [1200, 1500, ...]
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
