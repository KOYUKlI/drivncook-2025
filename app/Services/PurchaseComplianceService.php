<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\StockItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Compute and summarize 80/20 corporate-vs-free purchase share.
 * Notes:
 * - Scope decision: Report targets Replenishments only in controllers, but service supports any kind.
 * - Replenishment semantics: line unit_price_cents already equals line total; qty is treated as 1 for totals/ratio.
 * - Ignore cancelled/empty lines: qty<=0 or unit_price_cents<=0 are excluded from sums (treated as cancelled).
 */
class PurchaseComplianceService
{
    /**
     * Calculate the 80/20 ratio for corporate vs franchisee items.
     *
     * @param  array  $lines  Array of purchase order lines with stock_item_id, qty, unit_price_cents
     * @return float Percentage compliance (0-100), rounded to 2 decimals
     */
    public function ratio8020(array $lines): float
    {
        if (empty($lines)) {
            return 0.00;
        }

        // Build central flag map in one query (acts like a join)
        $stockItemIds = array_unique(array_column($lines, 'stock_item_id'));
        $isCentralById = empty($stockItemIds)
            ? []
            : StockItem::whereIn('id', $stockItemIds)->pluck('is_central', 'id')->toArray();

        $totalCents = 0;
        $centralCents = 0;

        foreach ($lines as $l) {
            $qty = max(0, (int)($l['qty'] ?? 0));
            $priceCents = max(0, (int)($l['unit_price_cents'] ?? 0));
            // Ignore cancelled/empty lines (qty<=0 or price<=0)
            if ($qty === 0 || $priceCents === 0) {
                continue;
            }

            $lineCents = $qty * $priceCents;
            $totalCents += $lineCents;

            if (!empty($l['stock_item_id']) && ($isCentralById[$l['stock_item_id']] ?? false)) {
                $centralCents += $lineCents;
            }
        }

        if ($totalCents === 0) {
            return 0.00; // Explicit: total=0 → ratio=0, no warnings
        }

        return round(($centralCents / $totalCents) * 100, 2);
    }
    
    /**
     * Calculate the 80/20 ratio for a purchase order
     * The ratio is the percentage of central items value compared to the total order value
     *
     * @param PurchaseOrder $purchaseOrder
     * @return float Percentage value (0-100)
     */
    public function getRatio(PurchaseOrder $purchaseOrder): float
    {
        // Normalize lines: for Replenishments, unit_price_cents already represents the line total
        // so we set qty=1 to avoid multiplying. For other kinds, use qty * unit_price_cents.
        $lines = $purchaseOrder->lines->map(function ($line) use ($purchaseOrder) {
            $isRepl = ($purchaseOrder->kind === 'Replenishment');
            return [
                'stock_item_id' => $line->stock_item_id,
                'qty' => $isRepl ? 1 : (int)($line->qty ?? 0),
                'unit_price_cents' => (int) ($line->unit_price_cents ?? 0),
            ];
        })->toArray();

        return $this->ratio8020($lines);
    }
    
    /**
     * Check if a purchase order is compliant with the 80/20 rule
     *
     * @param PurchaseOrder $purchaseOrder
     * @return bool
     */
    public function isCompliant(PurchaseOrder $purchaseOrder): bool
    {
        return $this->getRatio($purchaseOrder) >= 80;
    }
    
    /**
     * Get compliance data for multiple purchase orders
     *
     * @param Collection $purchaseOrders
     * @return array
     */
    public function getComplianceData(Collection $purchaseOrders): array
    {
        $compliantCount = 0;
        $totalRatio = 0.0;

        foreach ($purchaseOrders as $order) {
            $ratio = $this->getRatio($order);
            if ($ratio >= 80.0) {
                $compliantCount++;
            }
            $totalRatio += $ratio;
        }

        $count = max(0, $purchaseOrders->count());

        return [
            'orders' => [], // Not used by consumers; list is provided separately
            'metrics' => [
                'total_count' => $count,
                'compliant_count' => $compliantCount,
                'non_compliant_count' => max(0, $count - $compliantCount),
                'compliance_rate' => $count > 0 ? round(($compliantCount / $count) * 100, 2) : 0.0,
                'average_ratio' => $count > 0 ? round($totalRatio / $count, 2) : 0.0,
            ],
        ];
    }
    
    /**
     * Get compliance data grouped by franchisee
     *
     * @param Collection $purchaseOrders
     * @return array
     */
    public function getComplianceByFranchisee(Collection $purchaseOrders): array
    {
        $by = [];

        foreach ($purchaseOrders as $order) {
            $fid = $order->franchisee_id ?? 'admin';
            $fname = $order->franchisee->name ?? 'Admin Orders';

            $rec = $by[$fid] ?? ['name' => $fname, 'orders_count' => 0, 'compliant_count' => 0, 'total_ratio' => 0.0];
            $ratio = $this->getRatio($order);
            $rec['orders_count']++;
            $rec['total_ratio'] += $ratio;
            if ($ratio >= 80.0) { $rec['compliant_count']++; }
            $by[$fid] = $rec;
        }

        return collect($by)->map(function ($d) {
            $orders = max(0, (int)$d['orders_count']);
            $avg = $orders > 0 ? round($d['total_ratio'] / $orders, 2) : 0.0;
            return [
                'name' => $d['name'],
                'orders_count' => $orders,
                'compliant_count' => (int) $d['compliant_count'],
                'avg_ratio' => $avg,
                'compliance_rate' => $orders > 0 ? round(($d['compliant_count'] / $orders) * 100, 2) : 0.0,
                'is_compliant' => $avg >= 80.0,
            ];
        })->values()->all();
    }

    /**
     * Compute total amount of an order in cents, respecting semantics:
     * - Replenishment: unit_price_cents already represents the line total.
     * - Other kinds: qty * unit_price_cents.
     */
    public function getOrderTotalCents(PurchaseOrder $order): int
    {
        $isRepl = ($order->kind === 'Replenishment');
        $sum = 0;
        foreach ($order->lines as $l) {
            $qty = $isRepl ? 1 : max(0, (int)($l->qty ?? 0));
            $price = max(0, (int)($l->unit_price_cents ?? 0));
            if ($qty === 0 || $price === 0) { continue; }
            $sum += $qty * $price;
        }
        return (int) $sum;
    }
}
