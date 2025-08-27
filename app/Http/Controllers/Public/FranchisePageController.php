<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class FranchisePageController extends Controller
{
    /**
     * Display franchise information page.
     */
    public function show()
    {
        return view('public.franchise-info');
    }
}
