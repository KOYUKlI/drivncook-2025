<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\Replenishments\StoreReplenishmentRequest;
use App\Http\Requests\Replenishments\UpdateReplenishmentStatusRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\StockMovement;
use App\Models\WarehouseInventory;
use App\Services\PdfService;
use App\Services\PurchaseComplianceService;
use App\Mail\ReplenishmentDelivered;
use App\Mail\ReplenishmentShipped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReplenishmentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', PurchaseOrder::class);

        $q = PurchaseOrder::replenishments()->with(['warehouse', 'franchisee', 'lines']);

        if ($status = $request->string('status')->trim()->toString()) {
            $q->where('status', $status);
        }
        if ($wid = $request->string('warehouse_id')->trim()->toString()) {
            $q->where('warehouse_id', $wid);
        }
        if ($fid = $request->string('franchisee_id')->trim()->toString()) {
            $q->where('franchisee_id', $fid);
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

        $orders = $q->latest()->paginate(20)->appends($request->query());

        $warehouses = \App\Models\Warehouse::orderBy('name')->get();
        $franchisees = \App\Models\Franchisee::orderBy('name')->get();
        $statuses = [
            'Draft','Approved','Picked','Shipped','Delivered','Closed','Cancelled'
        ];

        return view('bo.replenishments.index', compact('orders','warehouses','franchisees','statuses'));
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', PurchaseOrder::class);

        // Reuse filters
        $q = PurchaseOrder::replenishments()->with(['warehouse','franchisee','lines']);
        if ($status = $request->string('status')->trim()->toString()) { $q->where('status', $status); }
        if ($wid = $request->string('warehouse_id')->trim()->toString()) { $q->where('warehouse_id', $wid); }
        if ($fid = $request->string('franchisee_id')->trim()->toString()) { $q->where('franchisee_id', $fid); }
        if ($ref = $request->string('q')->trim()->toString()) { $q->where('reference', 'like', "%$ref%"); }
        if ($from = $request->date('from_date')) { $q->whereDate('created_at', '>=', $from->format('Y-m-d')); }
        if ($to = $request->date('to_date')) { $q->whereDate('created_at', '<=', $to->format('Y-m-d')); }

    $orders = $q->latest()->get();

        $filename = 'replenishments_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $delimiter = app()->getLocale() === 'fr' ? ';' : ',';
        $decimal = app()->getLocale() === 'fr' ? ',' : '.';

    return response()->streamDownload(function () use ($orders, $delimiter, $decimal) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM
            echo "\xEF\xBB\xBF";
            $cols = [
                __('ui.replenishments.csv.reference'),
                __('ui.replenishments.csv.franchisee'),
                __('ui.replenishments.csv.warehouse'),
                __('ui.replenishments.csv.status'),
                __('ui.replenishments.csv.total'),
                __('ui.replenishments.csv.ratio8020'),
                __('ui.replenishments.csv.created_at'),
                __('ui.replenishments.csv.shipped_at'),
                __('ui.replenishments.csv.delivered_at'),
            ];
            fputcsv($out, $cols, $delimiter);
            $sanitize = function ($value) {
                $str = (string) ($value ?? '');
                if ($str !== '' && preg_match('/^[=+\-@]/', $str)) {
                    $str = "'".$str;
                }
                return $str;
            };
            foreach ($orders as $po) {
                // For replenishments, treat unit_price_cents as line totals (no qty multiplication)
                $totalCents = $po->lines->sum(fn($l) => ($l->unit_price_cents ?? 0));
                $total = number_format($totalCents / 100, 2, $decimal, '');
                $shippedAt = optional($po->shipped_at)->toISOString();
                $deliveredAt = optional($po->delivered_at)->toISOString();
                fputcsv($out, [
                    $sanitize($po->reference ?? $po->id),
                    $sanitize($po->franchisee->name ?? ''),
                    $sanitize($po->warehouse->name ?? ''),
                    __('ui.replenishments.status.'.strtolower($po->status)),
                    $total,
                    $po->corp_ratio_cached !== null ? number_format((float)$po->corp_ratio_cached, 2, $decimal, '') : '',
                    optional($po->created_at)->toISOString(),
                    $shippedAt,
                    $deliveredAt,
                ], $delimiter);
            }
            fclose($out);
        }, $filename, $headers);
    }

    public function create()
    {
        $this->authorize('create', PurchaseOrder::class);

        $warehouses = \App\Models\Warehouse::orderBy('name')->get();
        $franchisees = \App\Models\Franchisee::orderBy('name')->get();
        $stockItems = \App\Models\StockItem::orderBy('name')->get();
        // Map inventories by warehouse for client-side filtering in the form
        $warehouseInventories = WarehouseInventory::with(['stockItem:id,name,unit,is_central'])
            ->select('warehouse_id', 'stock_item_id', 'qty_on_hand')
            ->get()
            ->groupBy('warehouse_id')
            ->map(function ($group) {
                return $group
                    ->filter(function ($inv) {
                        return (int) ($inv->qty_on_hand ?? 0) > 0; // only available items
                    })
                    ->map(function ($inv) {
                        return [
                            'id' => $inv->stock_item_id,
                            'name' => optional($inv->stockItem)->name ?? '',
                            'unit' => optional($inv->stockItem)->unit ?? '',
                            'is_central' => (bool) (optional($inv->stockItem)->is_central ?? false),
                            'qty_on_hand' => (int) ($inv->qty_on_hand ?? 0),
                        ];
                    })->values();
            });

        return view('bo.replenishments.create', compact('warehouses', 'franchisees', 'stockItems', 'warehouseInventories'));
    }

    public function store(StoreReplenishmentRequest $request, PurchaseComplianceService $compliance)
    {
        $this->authorize('create', PurchaseOrder::class);

        $data = $request->validated();
        $lines = $data['lines'];

    $po = new PurchaseOrder();
        $po->id = (string) Str::ulid();
    $po->reference = PurchaseOrder::nextReference();
        $po->warehouse_id = $data['warehouse_id'];
        $po->franchisee_id = $data['franchisee_id'];
        $po->placed_by = Auth::id();
        $po->status = 'Draft';
        $po->kind = 'Replenishment';
        // Compute 80/20 ratio based on line totals (no qty multiplication)
        $linesForRatio = array_map(function ($l) {
            return [
                'stock_item_id' => $l['stock_item_id'],
                'qty' => 1,
                'unit_price_cents' => (int) $l['unit_price_cents'],
            ];
        }, $lines);
        $po->corp_ratio_cached = $compliance->ratio8020($linesForRatio);
        $po->save();

        foreach ($lines as $line) {
            $pol = new PurchaseOrderLine();
            $pol->id = (string) Str::ulid();
            $pol->purchase_order_id = $po->id;
            $pol->stock_item_id = $line['stock_item_id'];
            $pol->qty = (int) $line['qty'];
            $pol->unit_price_cents = (int) $line['unit_price_cents'];
            $pol->save();
        }

        return redirect()->route('bo.replenishments.show', $po->id)
            ->with('success', __('ui.replenishments.created'));
    }

    public function show(string $id)
    {
        $order = PurchaseOrder::replenishments()->with(['warehouse','franchisee','lines.stockItem'])->findOrFail($id);
        $this->authorize('view', $order);

        return view('bo.replenishments.show', compact('order'));
    }

    public function updateStatus(UpdateReplenishmentStatusRequest $request, string $id, PdfService $pdf)
    {
        $po = PurchaseOrder::replenishments()->with('lines.stockItem')->findOrFail($id);
    $this->authorize('updateStatus', $po);

        $to = $request->validated()['status'];

        // Workflow: Draft → Approved → Picked → Shipped → Delivered → Closed
        $map = [
            'Draft' => ['Approved', 'Cancelled'],
            'Approved' => ['Picked', 'Cancelled'],
            'Picked' => ['Shipped', 'Cancelled'],
            'Shipped' => ['Delivered'],
            'Delivered' => ['Closed'],
            'Closed' => [],
            'Cancelled' => [],
        ];

        if (!in_array($to, $map[$po->status] ?? [], true)) {
            return response()->json(['message' => __('ui.replenishments.invalid_transition')], 409);
        }

        // Cancel is forbidden after Shipped
        if ($to === 'Cancelled' && in_array($po->status, ['Shipped','Delivered','Closed'], true)) {
            return response()->json(['message' => __('ui.replenishments.invalid_transition')], 409);
        }

    DB::transaction(function () use ($po, $to, $request, $pdf) {
            $generatedDeliveryNote = null;
            if ($to === 'Picked') {
                // Generate picking PDF when order is prepared
                $path = 'replenishments/'.$po->id.'/PI-'.($po->reference ?? $po->id).'.pdf';
                $pdf->replenishmentPicking([
                    'order' => $po->fresh('warehouse','franchisee','lines.stockItem'),
                    'lines' => $po->lines,
                ], $path);
            }
            if ($to === 'Shipped') {
                $this->performShipWithdrawals($po, $request->input('ship', []));

                // Generate delivery note PDF (BL) at shipment
                $path = 'replenishments/'.$po->id.'/BL-'.($po->reference ?? $po->id).'.pdf';
                $pdf->replenishmentDeliveryNote([
                    'order' => $po->fresh('warehouse','franchisee','lines.stockItem'),
                    'lines' => $po->lines,
                ], $path);
                $generatedDeliveryNote = $path;
            }

            if ($to === 'Delivered') {
                $this->applyDeliveredQuantities($po, $request->input('receive', []));
            }

            $po->status = $to;
            $po->status_updated_at = now();
            $po->status_updated_by = Auth::id();
            if ($to === 'Shipped' && !$po->shipped_at) { $po->shipped_at = now(); }
            if ($to === 'Delivered' && !$po->delivered_at) { $po->delivered_at = now(); }
            $po->save();

            // Send emails based on transition
            try {
                if ($to === 'Shipped') {
                    // Queue shipped email
                    if ($po->franchisee?->email) {
                        Mail::to($po->franchisee->email)
                            ->queue((new ReplenishmentShipped($po->fresh('warehouse','franchisee','lines'), $generatedDeliveryNote))
                                ->onQueue('mail'));
                    }
                } elseif ($to === 'Delivered') {
                    if ($po->franchisee?->email) {
                        Mail::to($po->franchisee->email)
                            ->queue((new ReplenishmentDelivered($po->fresh('warehouse','franchisee','lines')))
                                ->onQueue('mail'));
                    }
                }
            } catch (\Throwable $e) {
                // Log but do not block transition
                Log::warning('Replenishment email send failed', ['po' => $po->id, 'to' => $to, 'error' => $e->getMessage()]);
            }
        });

        return redirect()->route('bo.replenishments.show', $po->id)
            ->with('success', __('ui.replenishments.status_updated'));
    }

    private function performShipWithdrawals(PurchaseOrder $po, array $payload): void
    {
        // Aggregate requested shipments by stock_item_id to enforce availability at item-level
        $requestedByItem = [];
        foreach ($po->lines as $line) {
            $qtyRequested = $payload['lines'][$line->id]['qty_shipped'] ?? null;
            if ($qtyRequested === null) { continue; }
            $qty = (int) $qtyRequested;
            if ($qty <= 0) { continue; }

            // Per-line upper bound: cannot exceed remaining to ship
            $maxShip = max(0, $line->qty - ($line->qty_shipped ?? 0));
            if ($qty > $maxShip) {
                abort(422, __('ui.replenishments.errors.ship_exceeds_order'));
            }

            $requestedByItem[$line->stock_item_id] = ($requestedByItem[$line->stock_item_id] ?? 0) + $qty;
        }

        if (empty($requestedByItem)) { return; }

        // Lock inventories for all items in a single FOR UPDATE pass
        $inventories = WarehouseInventory::where('warehouse_id', $po->warehouse_id)
            ->whereIn('stock_item_id', array_keys($requestedByItem))
            ->lockForUpdate()
            ->get()
            ->keyBy('stock_item_id');

        // Re-check availability under lock at the aggregate level
        foreach ($requestedByItem as $stockItemId => $qtySum) {
            $inv = $inventories->get($stockItemId);
            $available = $inv?->qty_on_hand ?? 0;
            if ($available < $qtySum) {
                abort(422, __('ui.replenishments.errors.insufficient_stock'));
            }
        }

        // Everything available: apply per-line movements and decrement inventories
        foreach ($po->lines as $line) {
            $qtyRequested = $payload['lines'][$line->id]['qty_shipped'] ?? null;
            if ($qtyRequested === null) { continue; }
            $qty = (int) $qtyRequested;
            if ($qty <= 0) { continue; }

            // Create withdrawal movement
            $movement = new StockMovement([
                'id' => (string) Str::ulid(),
                'warehouse_id' => $po->warehouse_id,
                'stock_item_id' => $line->stock_item_id,
                'type' => StockMovement::TYPE_WITHDRAWAL,
                'quantity' => $qty,
                'reason' => __('ui.replenishments.ship_reason', ['id' => $po->reference ?? $po->id]),
                'ref_type' => 'REPLENISHMENT_ORDER',
                'ref_id' => $po->id,
                'user_id' => Auth::id(),
            ]);
            $movement->save();

            // Update inventory (still under same transaction & lock)
            $inv = $inventories->get($line->stock_item_id);
            if (!$inv) {
                abort(422, __('ui.replenishments.errors.insufficient_stock'));
            }
            $inv->qty_on_hand -= $qty;
            if ($inv->qty_on_hand < 0) {
                abort(422, __('ui.replenishments.errors.insufficient_stock'));
            }
            $inv->save();

            // Update line shipped
            $line->qty_shipped = min(($line->qty_shipped ?? 0) + $qty, $line->qty);
            $line->save();
        }
    }

    private function applyDeliveredQuantities(PurchaseOrder $po, array $payload): void
    {
        foreach ($po->lines as $line) {
            $qtyRequested = $payload['lines'][$line->id]['qty_delivered'] ?? null;
            if ($qtyRequested === null) { continue; }
            $qty = (int) $qtyRequested;
            if ($qty <= 0) { continue; }
            // Bounds: delivered cannot exceed shipped, and cannot go negative
            $maxDeliver = max(0, ($line->qty_shipped ?? 0) - ($line->qty_delivered ?? 0));
            if ($qty > $maxDeliver) {
                abort(422, __('ui.replenishments.errors.deliver_exceeds_shipped'));
            }
            $line->qty_delivered = min(($line->qty_delivered ?? 0) + $qty, ($line->qty_shipped ?? 0));
            $line->save();
        }
    }

    /**
     * Download picking sheet PDF (BO only).
     */
    public function downloadPicking(string $id, PdfService $pdf)
    {
        $po = PurchaseOrder::replenishments()->with(['warehouse','franchisee','lines.stockItem'])->findOrFail($id);
        $this->authorize('view', $po);
        $path = 'replenishments/'.$po->id.'/PI-'.($po->reference ?? $po->id).'.pdf';
    if (!Storage::disk('public')->exists($path)) {
            $pdf->replenishmentPicking(['order' => $po, 'lines' => $po->lines], $path);
        }
    return response()->download(Storage::disk('public')->path($path));
    }

    /**
     * Download delivery note PDF (BO only). Generates on the fly if missing.
     */
    public function downloadDeliveryNote(string $id, PdfService $pdf)
    {
        $po = PurchaseOrder::replenishments()->with(['warehouse','franchisee','lines.stockItem'])->findOrFail($id);
        $this->authorize('view', $po);
        $path = 'replenishments/'.$po->id.'/BL-'.($po->reference ?? $po->id).'.pdf';
    if (!Storage::disk('public')->exists($path)) {
            $pdf->replenishmentDeliveryNote(['order' => $po, 'lines' => $po->lines], $path);
        }
    return response()->download(Storage::disk('public')->path($path));
    }
}
