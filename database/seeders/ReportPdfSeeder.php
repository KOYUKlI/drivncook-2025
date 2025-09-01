<?php

namespace Database\Seeders;

use App\Models\Franchisee;
use App\Models\ReportPdf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportPdfSeeder extends Seeder
{
    public function run(): void
    {
        $franchisees = Franchisee::pluck('id');
        if ($franchisees->isEmpty()) { return; }

        $year = (int) now('UTC')->year;
        $months = [now('UTC')->subMonth()->month, now('UTC')->subMonths(2)->month];
        foreach ($franchisees as $fid) {
            foreach ($months as $m) {
                ReportPdf::firstOrCreate(
                    [
                        'franchisee_id' => $fid,
                        'type' => 'monthly_sales',
                        'year' => $year,
                        'month' => $m,
                    ],
                    [
                        'id' => (string) Str::ulid(),
                        'storage_path' => 'reports/'.$year.sprintf('%02d', $m).'/placeholder.pdf',
                        'generated_at' => now('UTC')->subDays(random_int(1, 10)),
                    ]
                );
            }
        }
    }
}
