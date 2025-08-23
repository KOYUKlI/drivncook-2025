<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExportController extends Controller
{
    public function salesPdf()
    {
        $query = CustomerOrder::with('truck.franchise')->orderByDesc('ordered_at');
        // If a franchise user triggers this export (via franchise route), scope to their franchise
        $user = Auth::user();
        if ($user && $user->role === 'franchise' && $user->franchise_id) {
            $query->whereHas('truck', fn($q)=>$q->where('franchise_id', $user->franchise_id));
        }
        $orders = $query->limit(200)->get();

        $html = view('admin.sales.pdf', compact('orders'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $disposition = request()->boolean('download') ? 'attachment' : 'inline';
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition . '; filename="sales.pdf"',
        ]);
    }
}
