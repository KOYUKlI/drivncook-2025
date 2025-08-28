<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\ReportPdf;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        return view('bo.reports.monthly');
    }

    public function generate(Request $request, PdfService $pdf)
    {
        $year = now()->year;
        $month = now()->month;
        $data = [
            'franchisee' => ['name' => 'Franchise Démo'],
            'month' => $month,
            'year' => $year,
            'total' => 12345,
            'lines' => [],
        ];
        $path = 'reports/demo/monthly-'.now()->format('Ym').'.pdf';
        $pdf->monthlySales($data, $path);

        $report = ReportPdf::create([
            'id' => (string) Str::ulid(),
            'franchisee_id' => request('franchisee_id') ?? (Auth::user()->franchisee_id ?? null),
            'type' => 'monthly_sales',
            'year' => $year,
            'month' => $month,
            'storage_path' => $path,
            'generated_at' => now(),
        ]);

        return response()->download(Storage::disk('public')->path($report->storage_path));
    }
}
