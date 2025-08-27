<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders with 80/20 ratio analysis.
     */
    public function index()
    {
        // Mock data
        $orders = [
            [
                'id' => 1,
                'reference' => 'PO-2024-001',
                'franchisee' => 'Paris Nord',
                'total' => 2500, // centimes
                'ratio_80_20' => 85, // pourcentage
                'status' => 'completed',
                'date' => '2024-08-25',
                'compliance' => 'compliant' // >= 80%
            ],
            [
                'id' => 2,
                'reference' => 'PO-2024-002',
                'franchisee' => 'Lyon Centre',
                'total' => 1800,
                'ratio_80_20' => 75, // En dessous de 80% = rouge
                'status' => 'pending',
                'date' => '2024-08-26',
                'compliance' => 'non_compliant' // < 80%
            ],
            [
                'id' => 3,
                'reference' => 'PO-2024-003',
                'franchisee' => 'Marseille Sud',
                'total' => 3200,
                'ratio_80_20' => 88,
                'status' => 'completed',
                'date' => '2024-08-27',
                'compliance' => 'compliant'
            ],
        ];

        return view('bo.purchase_orders.index', compact('orders'));
    }

    /**
     * Display the specified purchase order with detailed 80/20 analysis.
     */
    public function show(string $id)
    {
        // Mock data
        $order = [
            'id' => $id,
            'reference' => 'PO-2024-001',
            'franchisee' => 'Paris Nord',
            'franchisee_email' => 'franchise.parisnord@drivncook.fr',
            'total' => 2500,
            'ratio_80_20' => 85,
            'status' => 'completed',
            'date' => '2024-08-25',
            'lines' => [
                ['item' => 'Pain burger', 'category' => 'obligatoire', 'quantity' => 100, 'price' => 150, 'total' => 15000],
                ['item' => 'Steaks surgelés', 'category' => 'obligatoire', 'quantity' => 50, 'price' => 200, 'total' => 10000],
                ['item' => 'Sauce spéciale', 'category' => 'libre', 'quantity' => 10, 'price' => 80, 'total' => 800],
            ],
        ];

        // Calculate detailed ratios
        $obligatoireTotal = 0;
        $libreTotal = 0;
        
        foreach ($order['lines'] as $line) {
            if ($line['category'] === 'obligatoire') {
                $obligatoireTotal += $line['total'];
            } else {
                $libreTotal += $line['total'];
            }
        }

        $order['obligatoire_total'] = $obligatoireTotal;
        $order['libre_total'] = $libreTotal;
        $order['calculated_ratio'] = $obligatoireTotal > 0 ? 
            round(($obligatoireTotal / ($obligatoireTotal + $libreTotal)) * 100) : 0;
        $order['compliance_status'] = $order['calculated_ratio'] >= 80 ? 'compliant' : 'non_compliant';

        return view('bo.purchase_orders.show', compact('order'));
    }

    /**
     * Validate purchase order 80/20 ratio compliance.
     */
    public function validateCompliance(PurchaseOrderRequest $request, string $id)
    {
        // Mock data fetch with central ratio computation
        $order = $this->fetchOrderWithRatio($id);

        $action = $request->input('action');
        $message = $request->input('message', '');
        $overrideReason = $request->input('override_reason', '');

        switch ($action) {
            case 'approve':
                // Force approve despite non-compliance with audit trail
                $this->logComplianceOverride($id, $overrideReason, $order['central_ratio']);
                return redirect()
                    ->route('bo.purchase-orders.show', $id)
                    ->with('success', 'Bon de commande approuvé malgré la non-conformité 80/20.');

            case 'flag':
                // Flag for review but allow processing
                $this->flagForReview($id, $message, $order['central_ratio']);
                return redirect()
                    ->route('bo.purchase-orders.show', $id)
                    ->with('warning', 'Bon de commande signalé pour non-conformité 80/20.');

            case 'reject':
                // Reject and require modification
                $this->rejectOrder($id, $message, $order['central_ratio']);
                return redirect()
                    ->route('bo.purchase-orders.index')
                    ->with('success', 'Bon de commande rejeté. Le franchisé a été notifié des modifications requises.');
        }

        return redirect()->back();
    }

    /**
     * Update central ratio manually for a purchase order.
     */
    public function updateRatio(PurchaseOrderRequest $request, string $id)
    {
        $centralRatio = $request->input('central_ratio');
        $reason = $request->input('reason');

        // Update central ratio with audit trail
        $this->updateCentralRatio($id, $centralRatio, $reason);

        return redirect()
            ->route('bo.purchase-orders.show', $id)
            ->with('success', "Ratio central mis à jour à {$centralRatio}%.");
    }

    /**
     * Recalculate central ratio based on current inventory.
     */
    public function recalculate(PurchaseOrderRequest $request, string $id)
    {
        $forceRecalculation = $request->input('force_recalculation', false);

        // Recalculate central ratio based on current stock levels
        $newRatio = $this->computeCentralRatio($id, $forceRecalculation);

        return redirect()
            ->route('bo.purchase-orders.show', $id)
            ->with('success', "Ratio central recalculé: {$newRatio}%.");
    }

    /**
     * Fetch order with computed central ratio.
     */
    private function fetchOrderWithRatio(string $id): array
    {
        // Mock data with central ratio computation
        $order = [
            'id' => $id,
            'reference' => 'PO-2024-002',
            'franchisee' => 'Lyon Centre',
            'franchisee_email' => 'franchise.lyon@drivncook.fr',
            'ratio_80_20' => 75, // Original ratio
            'status' => 'pending'
        ];

        // Compute central ratio (80% from central warehouse, 20% local)
        $order['central_ratio'] = $this->computeCentralRatio($id);
        $order['compliance_status'] = $order['central_ratio'] >= 80 ? 'compliant' : 'non_compliant';

        return $order;
    }

    /**
     * Compute central ratio based on 80/20 rule.
     */
    private function computeCentralRatio(string $id, bool $forceRecalculation = false): float
    {
        // Mock computation - in real app, this would query actual inventory
        $centralStock = 850; // Items available in central warehouse
        $totalRequired = 1000; // Total items needed

        $centralRatio = ($centralStock / $totalRequired) * 100;

        // Ensure minimum 80% central sourcing
        return min(max($centralRatio, 80.0), 100.0);
    }

    /**
     * Log compliance override for audit trail.
     */
    private function logComplianceOverride(string $id, string $reason, float $ratio): void
    {
        // Mock logging - in real app, this would save to database
        Log::info("Purchase Order {$id} compliance override", [
            'ratio' => $ratio,
            'reason' => $reason,
            'user' => auth()->user()?->email,
            'timestamp' => now()
        ]);
    }

    /**
     * Flag order for review.
     */
    private function flagForReview(string $id, string $message, float $ratio): void
    {
        // Mock flagging - in real app, this would create alerts
        Log::warning("Purchase Order {$id} flagged for review", [
            'ratio' => $ratio,
            'message' => $message,
            'user' => auth()->user()?->email
        ]);
    }

    /**
     * Reject order with notification.
     */
    private function rejectOrder(string $id, string $message, float $ratio): void
    {
        // Mock rejection - in real app, this would update status and notify
        Log::info("Purchase Order {$id} rejected", [
            'ratio' => $ratio,
            'message' => $message,
            'user' => auth()->user()?->email
        ]);
    }

    /**
     * Update central ratio manually.
     */
    private function updateCentralRatio(string $id, float $ratio, string $reason): void
    {
        // Mock update - in real app, this would update database
        Log::info("Purchase Order {$id} central ratio updated", [
            'new_ratio' => $ratio,
            'reason' => $reason,
            'user' => auth()->user()?->email
        ]);
    }

    /**
     * Generate compliance report for all purchase orders.
     */
    public function complianceReport(Request $request)
    {
        $period = $request->input('period', 'current_month');
        
        // Mock compliance data
        $complianceData = [
            'total_orders' => 45,
            'compliant_orders' => 38,
            'non_compliant_orders' => 7,
            'compliance_rate' => 84.4,
            'average_ratio' => 82.1,
            'by_franchisee' => [
                ['name' => 'Paris Nord', 'orders' => 8, 'compliance_rate' => 87.5, 'avg_ratio' => 85.2],
                ['name' => 'Lyon Centre', 'orders' => 6, 'compliance_rate' => 66.7, 'avg_ratio' => 76.8],
                ['name' => 'Marseille Sud', 'orders' => 7, 'compliance_rate' => 100, 'avg_ratio' => 89.1],
                ['name' => 'Toulouse Nord', 'orders' => 5, 'compliance_rate' => 80, 'avg_ratio' => 81.4],
            ],
            'trend' => [
                ['month' => 'Juin', 'compliance_rate' => 78.2],
                ['month' => 'Juillet', 'compliance_rate' => 81.5],
                ['month' => 'Août', 'compliance_rate' => 84.4],
            ]
        ];

        return view('bo.purchase_orders.compliance_report', compact('complianceData', 'period'));
    }
}
