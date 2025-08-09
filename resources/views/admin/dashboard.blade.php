@extends('layouts.app')

@section('content')
    <h1 class="page-title mb-6">Dashboard Administrateur</h1>

    <!-- Indicateurs clés -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">Total Trucks</h2>
            <p class="text-2xl font-semibold">{{ $truckCount }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">Total Warehouses</h2>
            <p class="text-2xl font-semibold">{{ $warehouseCount }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">Total Franchisees</h2>
            <p class="text-2xl font-semibold">{{ $franchiseCount }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">Total Sales (Count)</h2>
            <p class="text-2xl font-semibold">{{ $totalSalesCount }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">Total Sales (Revenue)</h2>
            <p class="text-2xl font-semibold">{{ number_format($totalSalesSum, 2) }} €</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 class="text-sm text-gray-500">Pending Stock Orders</h2>
            <p class="text-2xl font-semibold">{{ $pendingStockOrders }}</p>
        </div></div>
    </div>

    <!-- Graphique des ventes (exemple : ventes par mois) -->
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Sales Overview</h2>
        <canvas id="salesChart" width="400" height="200"></canvas>
    </div>

    <!-- (Optionnel) Tableau des dernières commandes de stock en attente -->
    @if(isset($latestPendingOrders))
                <div class="mt-8 card">
                        <div class="card-header">
                            <h2 class="text-lg font-bold">Recent Pending Stock Orders</h2>
                        </div>
                        <div class="card-body p-0">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Franchise</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestPendingOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->truck->franchise->name ?? '-' }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
