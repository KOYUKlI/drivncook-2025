<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class PdfService
{
    public function monthlySales(array $data, string $path): string
    {
        $html = View::make('reports.monthly_sales', $data)->render();
        $opts = new Options;
        $opts->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($opts);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        Storage::disk('public')->put($path, $dompdf->output());

        return Storage::disk('public')->path($path);
    }
}
