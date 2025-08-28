<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Sale;
use App\Models\SaleLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    /**
     * Display a listing of sales.
     */
    public function index(Request $request)
    {
    $user = Auth::user();
        $from = $request->input('from') ? now()->parse($request->input('from')) : now()->startOfMonth();
        $to = $request->input('to') ? now()->parse($request->input('to')) : now();

        $query = Sale::query()->whereBetween('created_at', [$from, $to]);
        $franchiseeId = data_get($user, 'franchisee_id');
        if ($franchiseeId) {
            $query->where('franchisee_id', $franchiseeId);
        }
        $sales = $query->with('lines')->latest()->get();

        $stats = [
            'period_from' => $from,
            'period_to' => $to,
            'count' => $sales->count(),
            'sum_cents' => $sales->sum('total_cents'),
        ];

        return view('fo.sales.index', compact('sales', 'stats'));
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
    public function store(StoreSaleRequest $request)
    {
        $validated = $request->validated();

        // Calculate total on server side
        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += (int) $item['quantity'] * (int) $item['unit_price'];
        }

        $sale = new Sale();
        $sale->id = (string) Str::ulid();
        $sale->franchisee_id = Auth::user()->franchisee_id ?? null;
        $sale->total_cents = $total;
        $sale->payment_method = $validated['payment_method'];
        $sale->notes = $validated['location'] ?? null;
        $sale->save();

        foreach ($validated['items'] as $item) {
            $line = new SaleLine();
            $line->id = (string) Str::ulid();
            $line->sale_id = $sale->id;
            $line->stock_item_id = null;
            $line->qty = (int) $item['quantity'];
            $line->unit_price_cents = (int) $item['unit_price'];
            $line->save();
        }

        return redirect()->route('fo.sales.index')
            ->with('success', __('Vente enregistrée (:amount€)', ['amount' => number_format($total / 100, 2, ',', ' ')]));
    }
}
