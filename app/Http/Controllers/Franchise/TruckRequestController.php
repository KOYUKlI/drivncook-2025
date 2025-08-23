<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\TruckRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TruckRequestController extends Controller
{
    public function index()
    {
        $franchiseId = Auth::user()->franchise_id;
        $requests = TruckRequest::where('franchise_id', $franchiseId)
            ->orderByDesc('created_at')->get();
        return view('franchise.truckrequests.index', compact('requests'));
    }

    public function create()
    {
        return view('franchise.truckrequests.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reason' => 'nullable|string|max:2000',
        ]);
        $user = Auth::user();
        TruckRequest::create([
            'franchise_id' => $user->franchise_id,
            'requested_by' => $user->id,
            'status' => 'pending',
            'reason' => $data['reason'] ?? null,
        ]);
        return redirect()->route('franchise.truckrequests.index')
            ->with('success', 'Your request has been submitted to the admin.');
    }
}
