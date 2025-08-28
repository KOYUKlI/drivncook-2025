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
            $data = [
                'franchisee' => ['name' => $f->name],
                'month' => $month,
                'year' => $year,
                'total' => 0,
                'lines' => [],
            ];

            $path = "reports/{$f->id}/monthly-{$year}-".str_pad((string) $month, 2, '0', STR_PAD_LEFT).'.pdf';
            $this->pdf->monthlySales($data, $path);

            ReportPdf::create([
                'id' => (string) Str::ulid(),
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
