<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class PdfService
{
    /**
     * Build the monthly sales view-model from DB.
     */
    public function buildMonthlySalesModel(?string $franchiseeId, int $year, int $month): array
    {
        $salesQ = \App\Models\Sale::query()
            ->when($franchiseeId, fn($q) => $q->where('franchisee_id', $franchiseeId))
            ->whereYear('sale_date', $year)
            ->whereMonth('sale_date', $month)
            ->orderBy('sale_date');
        $hasTruck = \Illuminate\Support\Facades\Schema::hasColumn('sales', 'truck_id');
        $select = ['id','sale_date','total_cents'];
        if ($hasTruck) { $select[] = 'truck_id'; }
        $sales = $salesQ->get($select);
        $total = (int) $sales->sum('total_cents');
        $count = (int) $sales->count();
        $avg = $count > 0 ? (int) floor($sales->avg('total_cents')) : 0;

        // Daily series (complete month)
        $start = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();
        $byDate = $sales->groupBy(fn($s) => optional($s->sale_date)->toDateString());
        $daily = [];
        for ($d = (clone $start); $d->lte($end); $d->addDay()) {
            $key = $d->toDateString();
            $grp = $byDate->get($key, collect());
            $sum = (int) $grp->sum('total_cents');
            $cnt = (int) $grp->count();
            $daily[] = [
                'date' => $key,
                'transactions' => $cnt,
                'total_cents' => $sum,
                'avg_ticket_cents' => $cnt > 0 ? (int) floor($sum / max(1, $cnt)) : 0,
            ];
        }

        // Observations
        $zeroDays = collect($daily)->filter(fn($r) => $r['total_cents'] === 0)->pluck('date')->values()->all();
        $best = collect($daily)->sortByDesc('total_cents')->first();

        // Optional per-truck breakdown if truck_id exists
    $perTruck = [];
    if ($hasTruck && $sales->firstWhere('truck_id', '!=', null)) {
            $byTruck = $sales->groupBy('truck_id')->map(function ($g, $truckId) use ($year, $month) {
                $totalC = (int) $g->sum('total_cents');
                $active = $g->groupBy(fn($s) => optional($s->sale_date)->toDateString())->filter()->count();
                $name = optional(\App\Models\Truck::find($truckId))->name ?? ('#'.$truckId);
                return ['truck_id' => $truckId, 'name' => $name, 'total_cents' => $totalC, 'active_days' => $active];
            })->values();
            $grand = max(1, (int) $byTruck->sum('total_cents'));
            $perTruck = $byTruck->map(fn($r) => $r + ['share' => $grand > 0 ? ($r['total_cents'] / $grand) : 0])
                                ->sortByDesc('total_cents')->values()->all();
        }

        // Optional top products
        $topProducts = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('sale_lines')) {
            $lines = \App\Models\SaleLine::query()
                ->whereHas('sale', function ($q) use ($franchiseeId, $year, $month) {
                    $q->when($franchiseeId, fn($qq) => $qq->where('franchisee_id', $franchiseeId))
                      ->whereYear('sale_date', $year)
                      ->whereMonth('sale_date', $month);
                })
                ->get(['stock_item_id','qty','unit_price_cents']);
            $byItem = $lines->groupBy('stock_item_id')->map(function ($g, $id) {
                $qty = (int) $g->sum('qty');
                $salesC = (int) $g->sum(fn($l) => (int)$l->qty * (int)$l->unit_price_cents);
                return ['stock_item_id' => $id, 'qty' => $qty, 'sales_cents' => $salesC];
            })->values();
            $grand = max(1, (int) $byItem->sum('sales_cents'));
            $topProducts = $byItem->map(function ($r) use ($grand) {
                $name = optional(\App\Models\StockItem::find($r['stock_item_id']))->name ?? '#'.$r['stock_item_id'];
                return $r + ['name' => $name, 'share' => $grand > 0 ? ($r['sales_cents'] / $grand) : 0];
            })->sortByDesc('sales_cents')->take(10)->values()->all();
        }

        $frName = $franchiseeId ? optional(\App\Models\Franchisee::find($franchiseeId))->name : __('ui.bo.reports.monthly_sales.all_franchisees');

        return [
            'year' => $year,
            'month' => $month,
            'generated_at' => now()->toDateTimeString(),
            'franchisee_name' => $frName,
            'kpis' => [
                'total_cents' => $total,
                'transactions' => $count,
                'avg_ticket_cents' => $avg,
                'active_days' => (int) (count($daily) - count($zeroDays)),
            ],
            'daily' => $daily,
            'per_truck' => $perTruck,
            'top_products' => $topProducts,
            'observations' => [
                'zero_days' => $zeroDays,
                'best_day' => $best ? ['date' => $best['date'], 'total_cents' => $best['total_cents']] : null,
            ],
        ];
    }
    /**
     * Generate Monthly Sales PDF, store it under the given storage path, and return absolute path.
     * Inputs: franchiseeId|null, year, month. Output: absolute path to stored PDF.
     */
    public function monthlySalesReport(?string $franchiseeId, int $year, int $month, string $path): string
    {
    $viewModel = $this->buildMonthlySalesModel($franchiseeId, $year, $month);
    $html = View::make('pdfs.reports.monthly_sales', ['viewModel' => $viewModel])->render();
    $opts = new Options;
    $opts->set('isRemoteEnabled', true);
    $opts->set('isPhpEnabled', true); // allow Dompdf PHP for page counters
    $opts->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($opts);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        Storage::disk('public')->put($path, $dompdf->output());
        return Storage::disk('public')->path($path);
    }
    // Deprecated: old signature kept for BC with deprecated callers, prefer monthlySalesReport above
    public function renderMonthly(array $data, string $path): string
    {
    $html = View::make('pdfs.reports.monthly_sales', ['viewModel' => $data])->render();
    $opts = new Options; 
    $opts->set('isRemoteEnabled', true);
    $opts->set('isPhpEnabled', true);
    $opts->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($opts);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        Storage::disk('public')->put($path, $dompdf->output());
        return Storage::disk('public')->path($path);
    }

    // Backward-compatibility: keep old signature name
    public function monthlySales(array $data, string $path): string
    {
        return $this->renderMonthly($data, $path);
    }

    /**
     * Generate and store a picking sheet PDF for a replenishment order.
     * @param array $data expects keys: order, lines
     * @param string $path relative storage path under public disk
     * @return string absolute local path
     */
    public function replenishmentPicking(array $data, string $path): string
    {
        $html = View::make('pdfs.replenishments.picking', $data)->render();
        $opts = new Options;
        $opts->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($opts);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        Storage::disk('public')->put($path, $dompdf->output());

        return Storage::disk('public')->path($path);
    }

    /**
     * Generate and store a delivery note (bon de livraison) PDF for a replenishment order.
     * @param array $data expects keys: order, lines
     * @param string $path relative storage path under public disk
     * @return string absolute local path
     */
    public function replenishmentDeliveryNote(array $data, string $path): string
    {
        $html = View::make('pdfs.replenishments.delivery_note', $data)->render();
        $opts = new Options;
        $opts->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($opts);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        Storage::disk('public')->put($path, $dompdf->output());

        return Storage::disk('public')->path($path);
    }
}
