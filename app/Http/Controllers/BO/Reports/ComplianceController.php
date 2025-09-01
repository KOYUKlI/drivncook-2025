<?php

namespace App\Http\Controllers\BO\Reports;

use App\Http\Controllers\Controller;
use App\Models\Franchisee;
use App\Models\PurchaseOrder;
use App\Services\PurchaseComplianceService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ComplianceController extends Controller
{
    public function index(Request $request, PurchaseComplianceService $service)
    {
        $this->authorize('viewAny', PurchaseOrder::class);

        $from = $request->date('from_date');
        $to = $request->date('to_date');
        $franchiseeId = $request->string('franchisee_id')->trim()->toString();
        $export = $request->boolean('export');

        // Scope: Replenishments only for this 80/20 compliance report
        $q = PurchaseOrder::query()->replenishments()
            ->with(['franchisee', 'lines'])
            ->when($from, fn($qq) => $qq->whereDate('created_at', '>=', $from->format('Y-m-d')))
            ->when($to, fn($qq) => $qq->whereDate('created_at', '<=', $to->format('Y-m-d')))
            ->when($franchiseeId, fn($qq) => $qq->where('franchisee_id', $franchiseeId));

        $orders = $q->latest()->get();

        // Precompute totals and ratios (fallback when cache missing) and attach to models
        foreach ($orders as $po) {
            $po->setAttribute('computed_total_cents', $service->getOrderTotalCents($po));
            if (is_null($po->corp_ratio_cached)) {
                $po->setAttribute('computed_ratio', $service->getRatio($po));
            }
        }

        if ($export) {
            $filename = 'compliance_'.now()->format('Ymd_His').'.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            return new StreamedResponse(function() use ($orders, $service) {
                $out = fopen('php://output', 'w');
                // UTF-8 BOM for Excel compatibility
                echo "\xEF\xBB\xBF";

                $delimiter = ';'; // Excel FR friendly
                $sanitize = function ($value) {
                    $str = (string) ($value ?? '');
                    // Prevent CSV injection
                    if ($str !== '' && preg_match('/^[=+\-@]/', $str) === 1) {
                        $str = "'".$str;
                    }
                    return $str;
                };

                // En-têtes professionnels en français
                fputcsv($out, [
                    'Référence',
                    'Franchisé',
                    'Date de création',
                    'Date d\'expédition',
                    'Date de livraison',
                    'Montant total (€)',
                    'Ratio 80/20 (%)',
                    'Statut de conformité',
                    'Articles référencés (€)',
                    'Articles hors catalogue (€)',
                ], $delimiter);

                foreach ($orders as $po) {
                    $total = (int) ($po->computed_total_cents ?? $service->getOrderTotalCents($po));
                    $ratio = (float) ($po->corp_ratio_cached ?? $po->computed_ratio ?? 0.0);

                    // Calcul des montants référencés et hors catalogue (en centimes)
                    $referenceAmount = (int) round($total * ($ratio / 100));
                    $nonReferenceAmount = $total - $referenceAmount;

                    // Détermination du statut de conformité
                    $complianceStatus = $ratio >= 80 ? 'Conforme' : 'Non conforme';

                    fputcsv($out, [
                        $sanitize($po->reference ?? $po->id),
                        $sanitize($po->franchisee->name ?? 'Non défini'),
                        optional($po->created_at)->format('d/m/Y H:i'),
                        optional($po->shipped_at)->format('d/m/Y H:i') ?: 'Non expédiée',
                        optional($po->delivered_at)->format('d/m/Y H:i') ?: 'Non livrée',
                        // Use decimal comma, no thousands separators
                        number_format($total / 100, 2, ',', ''),
                        number_format($ratio, 2, ',', ''),
                        $complianceStatus,
                        number_format($referenceAmount / 100, 2, ',', ''),
                        number_format($nonReferenceAmount / 100, 2, ',', ''),
                    ], $delimiter);
                }

                fclose($out);
            }, 200, $headers);
        }

        $franchisees = Franchisee::orderBy('name')->get();

        // Summary metrics via service helpers
        $compliance = $service->getComplianceData($orders);
        $byFranchisee = $service->getComplianceByFranchisee($orders);

        return view('bo.reports.compliance', [
            'orders' => $orders,
            'franchisees' => $franchisees,
            'metrics' => $compliance['metrics'] ?? [],
            'byFranchisee' => $byFranchisee,
            'filters' => [
                'from_date' => $from?->toDateString(),
                'to_date' => $to?->toDateString(),
                'franchisee_id' => $franchiseeId,
            ],
        ]);
    }
}
