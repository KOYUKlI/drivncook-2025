<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display the public home/landing page.
     */
    public function index()
    {
        return view('public.home');
    }
}
