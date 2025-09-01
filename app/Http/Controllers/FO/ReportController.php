<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Models\ReportPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports for the current franchisee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $franchisee = Auth::user()->franchisee;

        if (!$franchisee) {
            return redirect()->route('fo.dashboard')
                ->with('error', __('ui.fo.reports.messages.access_denied'));
        }

        // Parse filters
        $year = $request->input('year');
        $month = $request->input('month');

        // Build query
        $query = ReportPdf::query()
            ->where('franchisee_id', $franchisee->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc');

        // Apply filters
        if ($year) {
            $query->where('year', $year);
        }
        
        if ($month) {
            $query->where('month', $month);
        }

        // Get available years and months for filters
        $years = ReportPdf::where('franchisee_id', $franchisee->id)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        $months = ReportPdf::where('franchisee_id', $franchisee->id)
            ->distinct()
            ->orderBy('month')
            ->pluck('month');

        // Paginate results
        $reports = $query->paginate(10)->withQueryString();

        return view('fo.reports.index', compact('reports', 'years', 'months', 'year', 'month'));
    }

    /**
     * Download a specific report PDF.
     *
     * @param  \App\Models\ReportPdf  $reportPdf
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function download(ReportPdf $reportPdf)
    {
        $this->authorize('view', $reportPdf);

        if (!Storage::disk('public')->exists($reportPdf->storage_path)) {
            return redirect()->route('fo.reports.index')
                ->with('error', __('ui.fo.reports.messages.file_not_found'));
        }

        return response()->download(
            Storage::disk('public')->path($reportPdf->storage_path),
            "rapport_mensuel_{$reportPdf->year}_{$reportPdf->month}.pdf"
        );
    }
}
