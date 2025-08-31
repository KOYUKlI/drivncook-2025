<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\StockItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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
    
    /**
     * Calculate the 80/20 ratio for a purchase order
     * The ratio is the percentage of central items value compared to the total order value
     *
     * @param PurchaseOrder $purchaseOrder
     * @return float Percentage value (0-100)
     */
    public function getRatio(PurchaseOrder $purchaseOrder): float
    {
        $lines = $purchaseOrder->lines->map(function($line) {
            return [
                'stock_item_id' => $line->stock_item_id,
                'qty' => $line->quantity,
                'unit_price_cents' => $line->unit_price_cents
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
        $data = [];
        $compliantCount = 0;
        $totalRatio = 0;
        
        foreach ($purchaseOrders as $order) {
            $ratio = $this->getRatio($order);
            $isCompliant = $ratio >= 80;
            
            if ($isCompliant) {
                $compliantCount++;
            }
            
            $totalRatio += $ratio;
            
            $data[] = [
                'order_id' => $order->id,
                'order_number' => $order->number,
                'franchisee' => $order->franchisee->name ?? 'Admin Order',
                'date' => $order->order_date,
                'total' => $order->total_cents,
                'ratio' => $ratio,
                'is_compliant' => $isCompliant,
            ];
        }
        
        $count = $purchaseOrders->count();
        
        return [
            'orders' => $data,
            'metrics' => [
                'total_count' => $count,
                'compliant_count' => $compliantCount,
                'non_compliant_count' => $count - $compliantCount,
                'compliance_rate' => $count > 0 ? round(($compliantCount / $count) * 100, 2) : 0,
                'average_ratio' => $count > 0 ? round($totalRatio / $count, 2) : 0,
            ]
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
        $franchiseeData = [];
        
        foreach ($purchaseOrders as $order) {
            $franchiseeId = $order->franchisee_id ?? 'admin';
            $franchiseeName = $order->franchisee->name ?? 'Admin Orders';
            
            if (!isset($franchiseeData[$franchiseeId])) {
                $franchiseeData[$franchiseeId] = [
                    'name' => $franchiseeName,
                    'orders_count' => 0,
                    'compliant_count' => 0,
                    'total_ratio' => 0,
                ];
            }
            
            $ratio = $this->getRatio($order);
            $franchiseeData[$franchiseeId]['orders_count']++;
            $franchiseeData[$franchiseeId]['total_ratio'] += $ratio;
            
            if ($ratio >= 80) {
                $franchiseeData[$franchiseeId]['compliant_count']++;
            }
        }
        
        // Calculate averages
        foreach ($franchiseeData as &$data) {
            $data['avg_ratio'] = $data['orders_count'] > 0 
                ? round($data['total_ratio'] / $data['orders_count'], 2) 
                : 0;
                
            $data['compliance_rate'] = $data['orders_count'] > 0 
                ? round(($data['compliant_count'] / $data['orders_count']) * 100, 2) 
                : 0;
                
            $data['is_compliant'] = $data['avg_ratio'] >= 80;
        }
        
        return array_values($franchiseeData);
    }
}
