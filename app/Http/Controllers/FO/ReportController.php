<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index()
    {
        // Mock data
        $reports = [
            [
                'id' => 1,
                'type' => 'monthly',
                'period' => 'Août 2024',
                'status' => 'generated',
                'file_path' => 'reports/monthly-2024-08.pdf',
                'created_at' => '2024-08-31',
            ],
            [
                'id' => 2,
                'type' => 'monthly',
                'period' => 'Juillet 2024',
                'status' => 'generated',
                'file_path' => 'reports/monthly-2024-07.pdf',
                'created_at' => '2024-07-31',
            ],
            [
                'id' => 3,
                'type' => 'quarterly',
                'period' => 'Q2 2024',
                'status' => 'pending',
                'file_path' => null,
                'created_at' => null,
            ],
        ];

        return view('fo.reports.index', compact('reports'));
    }
}
