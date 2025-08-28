<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateReportRequest;
use App\Models\ReportPdf;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index()
    {
        $franchiseeId = data_get(Auth::user(), 'franchisee_id');
        $reports = ReportPdf::query()
            ->when($franchiseeId, fn($q) => $q->where('franchisee_id', $franchiseeId))
            ->latest('generated_at')
            ->get();

        return view('fo.reports.index', compact('reports'));
    }

    /**
     * Generate a new report based on user request.
     */
    public function generate(GenerateReportRequest $request, PdfService $pdfService)
    {
        $validated = $request->validated();

        // Mock report generation - in real app, would process based on validated parameters
        $reportId = '01HK'.strtoupper(str()->random(20));

        // Simulate processing based on report type
        switch ($validated['report_type']) {
            case 'sales':
                $this->generateSalesReport($validated, $pdfService, $reportId);
                break;
            case 'purchase_orders':
                $this->generatePurchaseOrdersReport($validated, $pdfService, $reportId);
                break;
            case 'trucks':
                $this->generateTrucksReport($validated, $pdfService, $reportId);
                break;
            case 'compliance':
                $this->generateComplianceReport($validated, $pdfService, $reportId);
                break;
        }

        return redirect()->route('fo.reports.index')
            ->with('success', "Rapport #{$reportId} généré avec succès en format {$validated['format']}.");
    }

    public function download(string $id)
    {
        $report = ReportPdf::findOrFail($id);
    $user = Auth::user();
    $isBackoffice = in_array(data_get($user, 'role'), ['admin','warehouse'], true); // fallback if roles not loaded
    $ownsReport = $user && $user->franchisee_id && $user->franchisee_id === $report->franchisee_id;
    if (! ($isBackoffice || $ownsReport)) {
            abort(403);
        }
        return response()->download(Storage::disk('public')->path($report->storage_path));
    }

    /**
     * Generate sales report.
     */
    private function generateSalesReport(array $params, PdfService $pdfService, string $reportId): void
    {
        // Mock sales data generation
        $salesData = [
            'report_id' => $reportId,
            'type' => 'sales',
            'period' => $params['period_type'],
            'total_sales' => 45000, // centimes
            'transaction_count' => 150,
            'average_transaction' => 300,
        ];

        // In real app: Generate actual PDF using PdfService
    }

    /**
     * Generate purchase orders report.
     */
    private function generatePurchaseOrdersReport(array $params, PdfService $pdfService, string $reportId): void
    {
        // Mock purchase order data generation
        $poData = [
            'report_id' => $reportId,
            'type' => 'purchase_orders',
            'compliance_rate' => 85.5,
            'total_orders' => 25,
            'average_ratio' => 82.3,
        ];

        // In real app: Generate actual PDF using PdfService
    }

    /**
     * Generate trucks utilization report.
     */
    private function generateTrucksReport(array $params, PdfService $pdfService, string $reportId): void
    {
        // Mock truck utilization data
        $trucksData = [
            'report_id' => $reportId,
            'type' => 'trucks',
            'utilization_rate' => 78.5,
            'total_deployments' => 85,
            'maintenance_days' => 12,
        ];

        // In real app: Generate actual PDF using PdfService
    }

    /**
     * Generate compliance report.
     */
    private function generateComplianceReport(array $params, PdfService $pdfService, string $reportId): void
    {
        // Mock compliance data
        $complianceData = [
            'report_id' => $reportId,
            'type' => 'compliance',
            'overall_compliance' => 92.1,
            'violations_count' => 3,
            'corrective_actions' => 8,
        ];

        // In real app: Generate actual PDF using PdfService
    }
}
