<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of sales.
     */
    public function index()
    {
        // Mock stats data
        $stats = [
            'today_sales' => 25000, // centimes
            'today_count' => 12,
            'week_sales' => 185000,
            'week_count' => 78,
            'month_sales' => 650000,
            'month_count' => 285,
            'best_location' => 'Place de la République',
            'best_location_sales' => 45000,
        ];

        // Mock data
        $sales = [
            [
                'id' => 1,
                'created_at' => '2024-08-27 14:30:00',
                'location' => 'Place de la République',
                'coordinates' => '48.8566, 2.3522',
                'payment_method' => 'card',
                'items_count' => 3,
                'total_amount' => 850, // centimes
            ],
            [
                'id' => 2,
                'created_at' => '2024-08-26 12:15:00',
                'location' => 'Gare du Nord',
                'coordinates' => '48.8809, 2.3553',
                'payment_method' => 'cash',
                'items_count' => 2,
                'total_amount' => 920,
            ],
            [
                'id' => 3,
                'created_at' => '2024-08-25 16:45:00',
                'location' => 'Châtelet',
                'coordinates' => '48.8583, 2.3472',
                'payment_method' => 'mobile',
                'items_count' => 4,
                'total_amount' => 750,
            ],
        ];

        $total_sales = count($sales);

        return view('fo.sales.index', compact('sales', 'stats', 'total_sales'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        // Mock products data
        $products = [
            ['id' => 1, 'name' => 'Burger Classic', 'price' => 950], // centimes
            ['id' => 2, 'name' => 'Sandwich Jambon', 'price' => 650],
            ['id' => 3, 'name' => 'Salade César', 'price' => 850],
            ['id' => 4, 'name' => 'Boisson 33cl', 'price' => 250],
            ['id' => 5, 'name' => 'Frites', 'price' => 350],
        ];

        return view('fo.sales.create', compact('products'));
    }

    /**
     * Store a newly created sale.
     */
    public function store(Request $request)
    {
        // Validation and storage logic here
        return redirect()->route('fo.sales.index')->with('success', 'Vente enregistrée avec succès');
    }
}
