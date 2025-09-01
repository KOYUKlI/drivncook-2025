<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\ReportPdf;
use App\Models\Sale;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        // Get filter parameters
        $year = $request->get('year', now()->year);
        $month = $request->get('month');
        $franchiseeId = $request->get('franchisee_id');

        // Build query for existing PDFs
        $reportsQuery = ReportPdf::where('type', 'monthly_sales')
            ->with('franchisee')
            ->when($year, fn ($q) => $q->where('year', $year))
            ->when($month, fn ($q) => $q->where('month', $month))
            ->when($franchiseeId, fn ($q) => $q->where('franchisee_id', $franchiseeId))
            ->latest('generated_at');

        $reports = $reportsQuery->get();

        // Get franchisees for filter dropdown
        $franchisees = \App\Models\Franchisee::orderBy('name')->get();

        // Get available years from reports
        $availableYears = ReportPdf::where('type', 'monthly_sales')
            ->distinct()
            ->pluck('year')
            ->sort()
            ->values();

        return view('bo.reports.monthly_sales', compact('reports', 'franchisees', 'availableYears', 'year', 'month', 'franchiseeId'));
    }

    public function generate(Request $request, PdfService $pdf)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'franchisee_id' => 'nullable|exists:franchisees,id',
        ]);

        $year = $request->get('year');
        $month = $request->get('month');
        $franchiseeId = $request->get('franchisee_id');

        // Check if report already exists
        $existingReport = ReportPdf::where('type', 'monthly_sales')
            ->where('year', $year)
            ->where('month', $month)
            ->where('franchisee_id', $franchiseeId)
            ->first();

        if ($existingReport) {
            return redirect()->route('bo.reports.monthly')
                ->with('warning', __('ui.bo.reports.monthly_sales.already_exists'));
        }

        // Get franchisee or use default
        $franchisee = $franchiseeId ? \App\Models\Franchisee::find($franchiseeId) : null;
        $franchiseeName = $franchisee?->name ?? __('ui.bo.reports.monthly_sales.all_franchisees');

        // Build sales dataset for the selected period (and optional franchisee)
        $salesQuery = Sale::query()
            ->when($franchiseeId, fn($q) => $q->where('franchisee_id', $franchiseeId))
            ->whereYear('sale_date', (int) $year)
            ->whereMonth('sale_date', (int) $month);

        $sales = $salesQuery->get(['id','sale_date','total_cents']);
        $totalSales = (int) $sales->sum('total_cents');
        $transactionCount = $sales->count();
        $avgTransaction = $transactionCount > 0 ? (int) floor($sales->avg('total_cents')) : 0;

        $dailySales = $sales
            ->groupBy(fn($s) => optional($s->sale_date)->toDateString())
            ->map(function ($group, $date) {
                $total = (int) $group->sum('total_cents');
                $count = $group->count();
                $avg = $count > 0 ? (int) floor($group->avg('total_cents')) : 0;
                return [
                    'date' => $date,
                    'transactions' => $count,
                    'total' => $total,
                    'avg' => $avg,
                ];
            })
            ->sortKeys()
            ->values()
            ->all();

        // New normalized path: reports/monthly/{year}/{month}/monthly-{year}-{month}-{ulid}.pdf
        $ulid = (string) \Illuminate\Support\Str::ulid();
        $ym = str_pad((string)$month, 2, '0', STR_PAD_LEFT);
        $filename = "monthly-{$year}-{$ym}-{$ulid}.pdf";
        $path = "reports/monthly/{$year}/{$ym}/{$filename}";

        // Build view model and delegate to PdfService
        $viewModel = [
            'year' => (int) $year,
            'month' => (int) $month,
            'generated_at' => now()->toDateTimeString(),
            'franchisee_name' => $franchiseeName,
            'kpis' => [
                'total_cents' => $totalSales,
                'transactions' => $transactionCount,
                'avg_ticket_cents' => $avgTransaction,
                'active_days' => collect($dailySales)->where('total', '>', 0)->count(),
            ],
            'daily' => collect($dailySales)->map(fn($d) => [
                'date' => $d['sale_date'] ?? $d['date'] ?? $d[0] ?? null,
                'transactions' => $d['transactions'] ?? $d['count'] ?? 0,
                'total_cents' => $d['total'] ?? $d['total_cents'] ?? 0,
                'avg_ticket_cents' => $d['avg'] ?? $d['avg_ticket_cents'] ?? 0,
            ])->values()->all(),
            'top_products' => [],
            'observations' => [],
        ];

    $pdf->monthlySalesReport($franchiseeId, (int)$year, (int)$month, $path);

    $report = ReportPdf::create([
            'id' => (string) Str::ulid(),
            'franchisee_id' => $franchiseeId,
            'type' => 'monthly_sales',
            'year' => $year,
            'month' => $month,
            'storage_path' => $path,
            'generated_at' => now(),
        ]);

        return redirect()->route('bo.reports.monthly')
            ->with('success', __('ui.bo.reports.monthly_sales.generated_success', [
                'month' => __('ui.months.'.$month),
                'year' => $year,
                'franchisee' => $franchiseeName,
            ]));
    }

    // Alias for clearer routing if needed in the BO
    public function generateMonthlySales(Request $request, PdfService $pdf)
    {
        return $this->generate($request, $pdf);
    }

    /**
     * Download an existing monthly report
     */
    public function download(string $id)
    {
        $report = ReportPdf::findOrFail($id);

        $this->authorize('downloadReport', $report);

        if (! Storage::disk('public')->exists($report->storage_path)) {
            return redirect()->back()
                ->with('error', __('ui.bo.reports.monthly_sales.file_not_found'));
        }

        return response()->download(Storage::disk('public')->path($report->storage_path));
    }
}
