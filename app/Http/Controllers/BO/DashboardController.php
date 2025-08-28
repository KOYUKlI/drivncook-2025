<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the back office dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Mock data for dashboard tiles
        $data = [
            'revenue_last_month' => 125000, // centimes
            'compliance_ratio' => 85, // pourcentage 80/20
            'trucks_in_maintenance' => 3,
            'pending_orders' => 12,
            'recent_events' => [
                ['type' => 'sale', 'description' => 'Nouvelle vente franchisé Paris Nord', 'time' => '2h'],
                ['type' => 'maintenance', 'description' => 'Camion C001 en maintenance programmée', 'time' => '4h'],
                ['type' => 'order', 'description' => 'Commande approvisionnement validée', 'time' => '6h'],
            ],
        ];

        return view('bo.dashboard', compact('data', 'user'));
    }
}
