<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\StockMovement;
use App\Models\WarehouseInventory;
use App\Services\PurchaseComplianceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FranchiseeOrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', PurchaseOrder::class);
        $q = PurchaseOrder::franchiseePo()->with(['franchisee','warehouse']);
        if ($status = $request->string('status')->trim()->toString()) { $q->where('status', $status); }
        if ($fid = $request->string('franchisee_id')->trim()->toString()) { $q->where('franchisee_id', $fid); }
        if ($wid = $request->string('warehouse_id')->trim()->toString()) { $q->where('warehouse_id', $wid); }
        if ($ref = $request->string('q')->trim()->toString()) { $q->where('reference','like',"%$ref%"); }
        if ($from = $request->date('from')) { $q->whereDate('created_at','>=',$from->format('Y-m-d')); }
        if ($to = $request->date('to')) { $q->whereDate('created_at','<=',$to->format('Y-m-d')); }
        $orders = $q->latest()->paginate(20)->appends($request->query());
        return view('bo.fo_orders.index', compact('orders'));
    }

    public function show(PurchaseOrder $order, PurchaseComplianceService $compliance)
    {
        $this->authorize('view', $order);
        abort_unless($order->kind === 'franchisee_po', 404);
        $order->load(['lines.stockItem','franchisee','warehouse']);
        $ratio = $compliance->getRatio($order);
        return view('bo.fo_orders.show', compact('order','ratio'));
    }

    public function approve(Request $request, PurchaseOrder $order)
    {
        $this->authorize('approve', $order);
        if ($order->status !== 'Submitted') { return response()->json(['message' => __('ui.bo.fo_orders.errors.invalid_transition')], 409); }
        $data = $request->validate(['warehouse_id' => ['required','exists:warehouses,id']]);
        $order->warehouse_id = $data['warehouse_id'];
        $order->status = 'Approved';
        $order->approved_at = now('UTC');
        $order->status_updated_at = now('UTC');
        $order->status_updated_by = Auth::id();
        $order->save();
        return back()->with('success', __('ui.bo.fo_orders.flash.approved'));
    }

    public function pick(Request $request, PurchaseOrder $order)
    {
        $this->authorize('pick', $order);
        if (!in_array($order->status, ['Approved','Picked'])) { return response()->json(['message' => __('ui.bo.fo_orders.errors.invalid_transition')], 409); }
        $data = $request->validate(['lines' => ['required','array']]);
        foreach ($data['lines'] as $lineId => $qty) {
            /** @var PurchaseOrderLine $line */
            $line = $order->lines()->where('id', $lineId)->firstOrFail();
            $q = max(0, (int)$qty);
            $line->qty_picked = min($line->qty, $q);
            $line->save();
        }
        $order->status = 'Picked';
        $order->status_updated_at = now('UTC');
        $order->status_updated_by = Auth::id();
        $order->save();
        return back()->with('success', __('ui.bo.fo_orders.flash.picked'));
    }

    public function ship(Request $request, PurchaseOrder $order)
    {
        $this->authorize('ship', $order);
        if (!in_array($order->status, ['Approved','Picked'])) { return response()->json(['message' => __('ui.bo.fo_orders.errors.invalid_transition')], 409); }
        if (!$order->warehouse_id) { return response()->json(['message' => __('ui.bo.fo_orders.errors.warehouse_required')], 422); }
        $data = $request->validate(['lines' => ['required','array']]);

        DB::transaction(function () use ($order, $data) {
            foreach ($data['lines'] as $lineId => $qty) {
                /** @var PurchaseOrderLine $line */
                $line = $order->lines()->where('id', $lineId)->lockForUpdate()->firstOrFail();
                $toShip = (int)$qty;
                $alreadyShipped = (int)($line->qty_shipped ?? 0);
                $availableToShip = max(0, (int)$line->qty - $alreadyShipped);
                if ($toShip <= 0 || $toShip > $availableToShip) {
                    abort(409, __('ui.bo.fo_orders.errors.ship_exceeds_order'));
                }

                // Lock inventory row
                $inv = WarehouseInventory::where('warehouse_id', $order->warehouse_id)
                    ->where('stock_item_id', $line->stock_item_id)
                    ->lockForUpdate()
                    ->first();
                if (!$inv || $inv->qty_on_hand < $toShip) {
                    abort(422, __('ui.bo.fo_orders.errors.insufficient_stock'));
                }
                $inv->qty_on_hand = $inv->qty_on_hand - $toShip;
                $inv->save();

                // Create stock movement
                StockMovement::create([
                    'warehouse_id' => $order->warehouse_id,
                    'stock_item_id' => $line->stock_item_id,
                    'type' => StockMovement::TYPE_WITHDRAWAL,
                    'quantity' => $toShip,
                    'reason' => 'FRANCHISEE_PO shipment',
                    'ref_type' => 'FRANCHISEE_PO',
                    'ref_id' => $order->id,
                    'user_id' => Auth::id(),
                ]);

                $line->qty_shipped = $alreadyShipped + $toShip;
                $line->save();
            }

            $order->status = 'Shipped';
            $order->shipped_at = now('UTC');
            $order->status_updated_at = now('UTC');
            $order->status_updated_by = Auth::id();
            $order->save();
        });

        return back()->with('success', __('ui.bo.fo_orders.flash.shipped'));
    }

    public function deliver(Request $request, PurchaseOrder $order)
    {
        $this->authorize('deliver', $order);
        if (!in_array($order->status, ['Shipped','Delivered'])) { return response()->json(['message' => __('ui.bo.fo_orders.errors.invalid_transition')], 409); }
        $data = $request->validate(['lines' => ['required','array']]);
        foreach ($data['lines'] as $lineId => $qty) {
            /** @var PurchaseOrderLine $line */
            $line = $order->lines()->where('id', $lineId)->firstOrFail();
            $toDeliver = (int)$qty;
            $alreadyDelivered = (int)($line->qty_delivered ?? 0);
            $availableToDeliver = max(0, (int)($line->qty_shipped ?? 0) - $alreadyDelivered);
            if ($toDeliver <= 0 || $toDeliver > $availableToDeliver) {
                abort(409, __('ui.bo.fo_orders.errors.deliver_exceeds_shipped'));
            }
            $line->qty_delivered = $alreadyDelivered + $toDeliver;
            $line->save();
        }
        $order->status = 'Delivered';
        $order->delivered_at = now('UTC');
        $order->status_updated_at = now('UTC');
        $order->status_updated_by = Auth::id();
        $order->save();
        return back()->with('success', __('ui.bo.fo_orders.flash.delivered'));
    }

    public function close(PurchaseOrder $order)
    {
        $this->authorize('close', $order);
        if (!in_array($order->status, ['Delivered','Closed'])) { return response()->json(['message' => __('ui.bo.fo_orders.errors.invalid_transition')], 409); }
        $order->status = 'Closed';
        $order->status_updated_at = now('UTC');
        $order->status_updated_by = Auth::id();
        $order->save();
        return back()->with('success', __('ui.bo.fo_orders.flash.closed'));
    }

    public function cancel(PurchaseOrder $order)
    {
        $this->authorize('cancel', $order);
        if (in_array($order->status, ['Shipped','Delivered','Closed'])) {
            return response()->json(['message' => __('ui.bo.fo_orders.errors.invalid_transition')], 409);
        }
        $order->status = 'Cancelled';
        $order->status_updated_at = now('UTC');
        $order->status_updated_by = Auth::id();
        $order->save();
        return back()->with('success', __('ui.bo.fo_orders.flash.cancelled'));
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', PurchaseOrder::class);
        $q = PurchaseOrder::franchiseePo()->with(['franchisee']);
        if ($status = $request->string('status')->trim()->toString()) { $q->where('status', $status); }
        if ($fid = $request->string('franchisee_id')->trim()->toString()) { $q->where('franchisee_id', $fid); }
        if ($ref = $request->string('q')->trim()->toString()) { $q->where('reference','like',"%$ref%"); }

        $filename = 'fo_orders_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $delimiter = app()->getLocale() === 'fr' ? ';' : ',';
        $decimal = app()->getLocale() === 'fr' ? ',' : '.';

        return response()->streamDownload(function () use ($q, $delimiter, $decimal) {
            $out = fopen('php://output', 'w');
            echo "\xEF\xBB\xBF"; // BOM
            fputcsv($out, [
                __('ui.replenishments.csv.reference'),
                __('ui.labels.franchisee'),
                __('ui.labels.status'),
                __('ui.labels.created_at'),
                __('ui.labels.total'),
            ], $delimiter);

            $sanitize = function ($value) {
                $str = (string) ($value ?? '');
                if ($str !== '' && preg_match('/^[=+\-@]/', $str)) { $str = "'".$str; }
                return $str;
            };

            $q->chunk(200, function ($rows) use ($out, $delimiter, $decimal, $sanitize) {
                foreach ($rows as $po) {
                    $total = number_format(((int)$po->total_cents) / 100, 2, $decimal, '');
                    fputcsv($out, [
                        $sanitize($po->reference),
                        $sanitize(optional($po->franchisee)->name),
                        $po->status,
                        optional($po->created_at)->toISOString(),
                        $total,
                    ], $delimiter);
                }
            });
            fclose($out);
        }, $filename, $headers);
    }
}
