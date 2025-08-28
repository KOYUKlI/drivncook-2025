<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the front office dashboard for franchisees.
     */
    public function index()
    {
        $user = Auth::user();

        // Mock data for franchisee dashboard
        $data = [
            'monthly_sales' => 15000, // centimes
            'sales_growth' => 8.5, // pourcentage
            'pending_orders' => 2,
            'truck_status' => 'active',
            'recent_sales' => [
                ['date' => '2024-08-27', 'amount' => 850, 'location' => 'Place de la République'],
                ['date' => '2024-08-26', 'amount' => 920, 'location' => 'Gare du Nord'],
                ['date' => '2024-08-25', 'amount' => 750, 'location' => 'Châtelet'],
            ],
            'quick_links' => [
                ['title' => 'Rapport mensuel PDF', 'route' => '#', 'icon' => 'document'],
                ['title' => 'Nouvelle commande', 'route' => 'fo.sales.create', 'icon' => 'plus'],
                ['title' => 'Mes rapports', 'route' => 'fo.reports.index', 'icon' => 'chart'],
            ],
        ];

        return view('fo.dashboard', compact('data', 'user'));
    }
}
