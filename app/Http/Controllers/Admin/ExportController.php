<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExportController extends Controller
{
    public function salesPdf()
    {
        $orders = CustomerOrder::with('truck.franchise')->orderByDesc('ordered_at')->limit(200)->get();

        $html = view('admin.sales.pdf', compact('orders'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="sales.pdf"',
        ]);
    }
}
