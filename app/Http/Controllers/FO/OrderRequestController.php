<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\StockItem;
use App\Services\PurchaseComplianceService;
use Illuminate\Http\Request;
use App\Http\Requests\FO\StoreFranchiseeOrderRequest;
use App\Http\Requests\FO\UpdateFranchiseeOrderRequest;
use App\Http\Requests\FO\SubmitFranchiseeOrderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $franchisee = $user->franchisee;
        abort_unless($franchisee, 403);

        $q = PurchaseOrder::franchiseePo()->with(['lines.stockItem'])
            ->forFranchisee($franchisee->id);

        if ($status = $request->string('status')->trim()->toString()) {
            $q->where('status', $status);
        }
        if ($from = $request->date('from')) {
            $q->whereDate('created_at', '>=', $from->format('Y-m-d'));
        }
        if ($to = $request->date('to')) {
            $q->whereDate('created_at', '<=', $to->format('Y-m-d'));
        }

        if ($request->boolean('export_csv')) {
            return $this->exportCsv($q);
        }

        $orders = $q->latest()->paginate(15)->appends($request->query());
        return view('fo.requests.index', compact('orders'));
    }

    public function create()
    {
        $this->authorize('create', PurchaseOrder::class);
        $items = StockItem::where('is_active', true)->orderBy('name')->get();
        return view('fo.requests.create', compact('items'));
    }

    public function store(StoreFranchiseeOrderRequest $request, PurchaseComplianceService $compliance)
    {
        $this->authorize('create', PurchaseOrder::class);
        $user = Auth::user();
        $franchisee = $user->franchisee;
        abort_unless($franchisee, 403);

        $validated = $request->validated();

        $po = new PurchaseOrder();
        $po->reference = PurchaseOrder::nextFpoReference();
        $po->franchisee_id = $franchisee->id;
        $po->placed_by = $user->id;
        $po->kind = 'franchisee_po';
        $po->status = 'Draft';
        $po->corp_ratio_cached = 0;

        DB::transaction(function () use ($po, $validated, $compliance) {
            $po->save();
            $total = 0;
            $linesForRatio = [];
            foreach ($validated['lines'] as $l) {
                $item = StockItem::findOrFail($l['stock_item_id']);
                $line = new PurchaseOrderLine();
                $line->purchase_order_id = $po->id;
                $line->stock_item_id = $item->id;
                $line->qty = (int)$l['qty'];
                $line->unit_price_cents = (int) ($item->price_cents ?? 0);
                $line->save();
                $total += $line->qty * $line->unit_price_cents;
                $linesForRatio[] = [
                    'stock_item_id' => $item->id,
                    'qty' => $line->qty,
                    'unit_price_cents' => $line->unit_price_cents,
                ];
            }
            $po->total_cents = (int)$total;
            $po->corp_ratio_cached = $compliance->ratio8020($linesForRatio);
            $po->save();
        });

        return redirect()->route('fo.orders.show', $po)->with('success', __('ui.fo.orders_request.flash.created'));
    }

    public function show(PurchaseOrder $order)
    {
        $this->authorize('view', $order);
        abort_unless($order->kind === 'franchisee_po', 404);
        $order->load(['lines.stockItem']);
        return view('fo.requests.show', compact('order'));
    }

    public function edit(PurchaseOrder $order)
    {
        $this->authorize('update', $order);
        abort_unless(in_array($order->status, ['Draft','Submitted']), 403);
        $items = StockItem::where('is_active', true)->orderBy('name')->get();
        $order->load(['lines.stockItem']);
        return view('fo.requests.edit', compact('order','items'));
    }

    public function update(UpdateFranchiseeOrderRequest $request, PurchaseOrder $order, PurchaseComplianceService $compliance)
    {
        $this->authorize('update', $order);
        abort_unless(in_array($order->status, ['Draft','Submitted']), 403);
        $validated = $request->validated();

        DB::transaction(function () use ($order, $validated, $compliance) {
            // reset lines
            $order->lines()->delete();
            $total = 0;
            $linesForRatio = [];
            foreach ($validated['lines'] as $l) {
                $item = StockItem::findOrFail($l['stock_item_id']);
                $line = new PurchaseOrderLine();
                $line->purchase_order_id = $order->id;
                $line->stock_item_id = $item->id;
                $line->qty = (int)$l['qty'];
                $line->unit_price_cents = (int) ($item->price_cents ?? 0);
                $line->save();
                $total += $line->qty * $line->unit_price_cents;
                $linesForRatio[] = [
                    'stock_item_id' => $item->id,
                    'qty' => $line->qty,
                    'unit_price_cents' => $line->unit_price_cents,
                ];
            }
            $order->total_cents = (int)$total;
            $order->corp_ratio_cached = $compliance->ratio8020($linesForRatio);
            $order->save();
        });

        return redirect()->route('fo.orders.show', $order)->with('success', __('ui.fo.orders_request.flash.updated'));
    }

    public function submit(SubmitFranchiseeOrderRequest $request, PurchaseOrder $order)
    {
        $this->authorize('submit', $order);
        if ($order->status !== 'Draft') {
            return response()->json(['message' => __('ui.fo.orders_request.errors.invalid_transition')], 409);
        }
        $order->status = 'Submitted';
        $order->submitted_at = now('UTC');
        $order->status_updated_at = now('UTC');
        $order->status_updated_by = Auth::id();
        $order->save();
        return redirect()->route('fo.orders.show', $order)->with('success', __('ui.fo.orders_request.flash.submitted'));
    }

    protected function exportCsv($query): StreamedResponse
    {
        $filename = 'fo_orders_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $delimiter = app()->getLocale() === 'fr' ? ';' : ',';
        $decimal = app()->getLocale() === 'fr' ? ',' : '.';

        return response()->streamDownload(function () use ($query, $delimiter, $decimal) {
            $out = fopen('php://output', 'w');
            echo "\xEF\xBB\xBF"; // BOM
            fputcsv($out, [
                __('ui.bo.fo_orders.csv.reference'),
                __('ui.labels.status'),
                __('ui.labels.created_at'),
                __('ui.labels.total'),
                __('ui.replenishments.csv.ratio8020'),
            ], $delimiter);

            $sanitize = function ($value) {
                $str = (string) ($value ?? '');
                if ($str !== '' && preg_match('/^[=+\-@]/', $str)) { $str = "'".$str; }
                return $str;
            };

            $query->chunk(200, function ($rows) use ($out, $delimiter, $decimal, $sanitize) {
                foreach ($rows as $po) {
                    $total = number_format(((int)$po->total_cents) / 100, 2, $decimal, '');
                    fputcsv($out, [
                        $sanitize($po->reference),
                        $po->status,
                        optional($po->created_at)->toISOString(),
                        $total,
                        number_format((float)$po->corp_ratio_cached, 2, $decimal, ''),
                    ], $delimiter);
                }
            });
            fclose($out);
        }, $filename, $headers);
    }
}
