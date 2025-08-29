<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\StockItem;
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

        // Calculate real sales stats
        $todaySales = Sale::whereDate('created_at', now()->toDateString())
            ->when($franchiseeId, fn ($q) => $q->where('franchisee_id', $franchiseeId))
            ->get();

        $weekSales = Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->when($franchiseeId, fn ($q) => $q->where('franchisee_id', $franchiseeId))
            ->get();

        $monthSales = Sale::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->when($franchiseeId, fn ($q) => $q->where('franchisee_id', $franchiseeId))
            ->get();

        $stats = [
            'period_from' => $from,
            'period_to' => $to,
            'count' => $sales->count(),
            'sum_cents' => $sales->sum('total_cents'),
            'today_sales' => $todaySales->sum('total_cents'),
            'today_count' => $todaySales->count(),
            'week_sales' => $weekSales->sum('total_cents'),
            'week_count' => $weekSales->count(),
            'month_sales' => $monthSales->sum('total_cents'),
            'month_count' => $monthSales->count(),
            'best_location' => 'Centre-ville', // TODO: Calculate from deployment data
            'best_location_sales' => $monthSales->max('total_cents') ?? 0,
        ];

        return view('fo.sales.index', compact('sales', 'stats'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $products = StockItem::select('id', 'name', 'price_cents as price')
            ->where('price_cents', '>', 0)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                ];
            });

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

        $sale = new Sale;
        $sale->id = (string) Str::ulid();
        $sale->franchisee_id = Auth::user()->franchisee_id ?? null;
        $sale->sale_date = now()->toDateString();
        $sale->total_cents = $total;
        $sale->save();

        foreach ($validated['items'] as $item) {
            $line = new SaleLine;
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
