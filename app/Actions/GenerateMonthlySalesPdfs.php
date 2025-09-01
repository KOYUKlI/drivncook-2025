<?php

namespace App\Actions;

use App\Models\Franchisee;
use App\Models\ReportPdf;
use App\Services\PdfService;
use Illuminate\Support\Str;

class GenerateMonthlySalesPdfs
{
    public function __construct(private PdfService $pdf) {}

    public function handle(): void
    {
        $period = now()->subMonth();
        $year = (int) $period->format('Y');
        $month = (int) $period->format('m');

        Franchisee::query()->each(function (Franchisee $f) use ($year, $month) {
            $ulid = (string) Str::ulid();
            $ym = str_pad((string) $month, 2, '0', STR_PAD_LEFT);
            $filename = "monthly-{$year}-{$ym}-{$ulid}.pdf";
            $path = "reports/monthly/{$year}/{$ym}/{$filename}";

            $this->pdf->monthlySalesReport($f->id, $year, $month, $path);

            ReportPdf::create([
                'id' => $ulid,
                'franchisee_id' => $f->id,
                'type' => 'monthly_sales',
                'year' => $year,
                'month' => $month,
                'storage_path' => $path,
                'generated_at' => now(),
            ]);
        });
    }
}
