<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    /**
     * List franchisee purchase orders (read-only).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $franchisee = $user->franchisee;

        abort_unless($franchisee, 403);

        $q = PurchaseOrder::replenishments()
            ->with(['warehouse', 'franchisee', 'lines'])
            ->where('franchisee_id', $franchisee->id);

        if ($status = $request->string('status')->trim()->toString()) {
            $q->where('status', $status);
        }
        if ($ref = $request->string('q')->trim()->toString()) {
            $q->where('reference', 'like', "%$ref%");
        }
        if ($from = $request->date('from_date')) {
            $q->whereDate('created_at', '>=', $from->format('Y-m-d'));
        }
        if ($to = $request->date('to_date')) {
            $q->whereDate('created_at', '<=', $to->format('Y-m-d'));
        }

        // CSV export (optional)
        if ($request->has('export') && $request->input('export') === 'csv') {
            return $this->exportCsv($q);
        }

        $orders = $q->latest()->paginate(15)->appends($request->query());
        $statuses = [
            'Draft','Approved','Picked','Shipped','Delivered','Closed','Cancelled'
        ];

        return view('fo.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Show order details.
     */
    public function show(string $id)
    {
        $order = PurchaseOrder::replenishments()->with(['warehouse','franchisee','lines.stockItem'])->findOrFail($id);
        $this->authorize('view', $order);

        return view('fo.orders.show', compact('order'));
    }

    /**
     * Download delivery note (BL) PDF if available.
     */
    public function downloadDeliveryNote(string $id)
    {
        $order = PurchaseOrder::replenishments()->with(['warehouse','franchisee'])->findOrFail($id);
        $this->authorize('view', $order);

        $path = 'replenishments/'.$order->id.'/BL-'.($order->reference ?? $order->id).'.pdf';
        if (!Storage::disk('public')->exists($path)) {
            abort(404, __('ui.flash.file_not_found'));
        }

        return response()->download(Storage::disk('public')->path($path));
    }

    /**
     * Export orders to CSV with locale-aware delimiter.
     */
    protected function exportCsv($query): StreamedResponse
    {
        $filename = 'orders_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $delimiter = app()->getLocale() === 'fr' ? ';' : ',';
        $decimal = app()->getLocale() === 'fr' ? ',' : '.';

        return response()->streamDownload(function () use ($query, $delimiter, $decimal) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM
            echo "\xEF\xBB\xBF";
            $headers = [
                __('ui.replenishments.csv.reference'),
                __('ui.labels.status'),
                __('ui.labels.created_at'),
                __('ui.labels.shipped_at'),
                __('ui.labels.delivered_at'),
                __('ui.replenishments.csv.total'),
            ];
            fputcsv($out, $headers, $delimiter);

            $sanitize = function ($value) {
                $str = (string) ($value ?? '');
                if ($str !== '' && preg_match('/^[=+\-@]/', $str)) {
                    $str = "'".$str;
                }
                return $str;
            };

            $query->chunk(200, function ($rows) use ($out, $delimiter, $decimal, $sanitize) {
                foreach ($rows as $po) {
                    $totalCents = $po->lines->sum(fn($l) => ($l->unit_price_cents ?? 0));
                    $total = number_format($totalCents / 100, 2, $decimal, '');
                    fputcsv($out, [
                        $sanitize($po->reference ?? $po->id),
                        __('ui.replenishments.status.'.strtolower($po->status)),
                        optional($po->created_at)->toISOString(),
                        optional($po->shipped_at)->toISOString(),
                        optional($po->delivered_at)->toISOString(),
                        $total,
                    ], $delimiter);
                }
            });

            fclose($out);
        }, $filename, $headers);
    }
}
