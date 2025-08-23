<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index()
    {
        $franchiseId = Auth::user()->franchise_id;
        $orders = CustomerOrder::whereHas('truck', fn($q)=>$q->where('franchise_id',$franchiseId))
            ->with('truck')
            ->orderByDesc('ordered_at')
            ->paginate(25);
        return view('franchise.sales.index', compact('orders'));
    }
}
