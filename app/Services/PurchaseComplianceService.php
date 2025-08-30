<?php

namespace App\Services;

use App\Models\StockItem;

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

        $totalValue = 0;
        $corporateValue = 0;

        // Get all stock items for the lines
        $stockItemIds = array_column($lines, 'stock_item_id');
        $stockItems = StockItem::whereIn('id', $stockItemIds)
            ->pluck('is_central', 'id')
            ->toArray();

        foreach ($lines as $line) {
            $lineValue = $line['qty'] * $line['unit_price_cents'];
            $totalValue += $lineValue;

            // Check if this stock item is corporate mandated
            $isCorporate = $stockItems[$line['stock_item_id']] ?? false;
            if ($isCorporate) {
                $corporateValue += $lineValue;
            }
        }

        if ($totalValue === 0) {
            return 0.00;
        }

        $ratio = ($corporateValue / $totalValue) * 100;

        return round($ratio, 2);
    }
}
