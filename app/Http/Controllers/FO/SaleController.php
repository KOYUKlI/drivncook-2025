<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\StockItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SaleController extends Controller
{
    /**
     * Display a listing of the sales.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Sale::class);

        $user = Auth::user();
        $franchisee = $user->franchisee;

        // Parse date filters
        $fromDate = $request->input('from_date') ? Carbon::parse($request->input('from_date')) : Carbon::now()->startOfMonth();
        $toDate = $request->input('to_date') ? Carbon::parse($request->input('to_date')) : Carbon::now()->endOfDay();

        // Build query
        $query = Sale::query()
            ->where('franchisee_id', $franchisee->id)
            ->whereBetween('sale_date', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ->orderBy('sale_date', 'desc');

        // Handle CSV export
        if ($request->has('export') && $request->input('export') === 'csv') {
            return $this->exportCsv($query, $fromDate, $toDate);
        }

        // Calculate stats
        $totalSales = $query->count();
        $totalAmount = $query->sum('total_cents') / 100;

        // Paginate results
        $sales = $query->paginate(10)->withQueryString();

        return view('fo.sales.index', compact('sales', 'totalSales', 'totalAmount', 'fromDate', 'toDate'));
    }

    /**
     * Show the form for creating a new sale.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Sale::class);

        $user = Auth::user();
        $franchisee = $user->franchisee;
        
        // Get available stock items
        $stockItems = StockItem::where('is_active', true)->orderBy('name')->get();
        
        return view('fo.sales.create', compact('stockItems'));
    }

    /**
     * Store a newly created sale in storage.
     *
     * @param  \App\Http\Requests\StoreSaleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSaleRequest $request)
    {
        $this->authorize('create', Sale::class);

        $user = Auth::user();
        $franchisee = $user->franchisee;
        
        // Create sale with recalculated total
        $sale = new Sale();
        $sale->id = (string) Str::uuid();
        $sale->franchisee_id = $franchisee->id;
        $sale->sale_date = $request->input('sale_date');
        
        // Calculate total from lines (will be double-checked by the FormRequest)
        $totalCents = 0;
        
        $sale->total_cents = $totalCents;
        $sale->save();
        
        // Create lines
        foreach ($request->input('lines', []) as $line) {
            $saleLine = new SaleLine();
            $saleLine->id = (string) Str::uuid();
            $saleLine->sale_id = $sale->id;
            $saleLine->stock_item_id = $line['stock_item_id'] ?? null;
            $saleLine->item_label = $line['item_label'] ?? null;
            $saleLine->qty = $line['qty'];
            $saleLine->unit_price_cents = $line['unit_price_cents'];
            $saleLine->save();
            
            // Add to total
            $totalCents += $line['qty'] * $line['unit_price_cents'];
        }
        
        // Update the sale with the calculated total
        $sale->total_cents = $totalCents;
        $sale->save();
        
        return redirect()->route('fo.sales.show', $sale->id)
            ->with('status', __('ui.fo.sales.flash.created_successfully'));
    }

    /**
     * Display the specified sale.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        $this->authorize('view', $sale);
        
        $sale->load('lines', 'franchisee');
        
        return view('fo.sales.show', compact('sale'));
    }

    /**
     * Export sales to CSV.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $fromDate
     * @param \Carbon\Carbon $toDate
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function exportCsv($query, $fromDate, $toDate)
    {
        $filename = 'sales_' . $fromDate->format('Ymd') . '_' . $toDate->format('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $delimiter = app()->getLocale() === 'fr' ? ';' : ',';
        $decimal = app()->getLocale() === 'fr' ? ',' : '.';

        return new StreamedResponse(function() use ($query, $delimiter, $decimal) {
            $handle = fopen('php://output', 'w');

            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Prevent CSV injection
            $sanitize = function ($value) {
                $str = (string) ($value ?? '');
                if ($str !== '' && preg_match('/^[=+\-@]/', $str)) {
                    return "'".$str;
                }
                return $str;
            };

            // Add headers
            fputcsv($handle, [
                __('ui.fo.sales.export.date'),
                __('ui.fo.sales.export.items_count'),
                __('ui.fo.sales.export.total')
            ], $delimiter);

            // Get all sales for export
            $query->chunk(200, function ($sales) use ($handle, $delimiter, $decimal, $sanitize) {
                foreach ($sales as $sale) {
                    fputcsv($handle, [
                        $sanitize($sale->sale_date->format('Y-m-d')),
                        $sale->lines()->count(),
                        number_format($sale->total_cents / 100, 2, $decimal, '')
                    ], $delimiter);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
