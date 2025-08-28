<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\ReportPdf;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        // Calculate real monthly sales data
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        
        $sales = \App\Models\Sale::whereBetween('created_at', [$monthStart, $monthEnd])->get();
        
        $totalSales = $sales->sum('total_cents');
        $transactionCount = $sales->count();
        $avgTransaction = $transactionCount > 0 ? $totalSales / $transactionCount : 0;

        // Calculate daily sales data
        $dailySales = $sales->groupBy(function ($sale) {
            return $sale->created_at->format('Y-m-d');
        })->map(function ($daySales, $date) {
            $dayTotal = $daySales->sum('total_cents');
            $dayCount = $daySales->count();
            
            return [
                'date' => $date,
                'transactions' => $dayCount,
                'total' => $dayTotal,
                'avg' => $dayCount > 0 ? $dayTotal / $dayCount : 0,
            ];
        })->values()->toArray();

        return view('reports.monthly_sales', compact(
            'totalSales',
            'transactionCount',
            'avgTransaction',
            'dailySales'
        ));
    }

    public function generate(Request $request, PdfService $pdf)
    {
        $year = now()->year;
        $month = now()->month;
        $data = [
            'franchisee' => ['name' => 'Franchise Démo'],
            'month' => $month,
            'year' => $year,
            'total' => 12345,
            'lines' => [],
        ];
        $path = 'reports/demo/monthly-'.now()->format('Ym').'.pdf';
        $pdf->monthlySales($data, $path);

        $report = ReportPdf::create([
            'id' => (string) Str::ulid(),
            'franchisee_id' => request('franchisee_id') ?? (Auth::user()->franchisee_id ?? null),
            'type' => 'monthly_sales',
            'year' => $year,
            'month' => $month,
            'storage_path' => $path,
            'generated_at' => now(),
        ]);

        return response()->download(Storage::disk('public')->path($report->storage_path));
    }
}
