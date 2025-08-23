<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TruckRequest;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TruckRequestController extends Controller
{
    public function index()
    {
        $requests = TruckRequest::with(['franchise','requester','handler'])
            ->orderByDesc('created_at')->paginate(20);
        return view('admin.truckrequests.index', compact('requests'));
    }

    public function show(TruckRequest $truckrequest)
    {
        $truckrequest->load(['franchise','requester','handler']);
        return view('admin.truckrequests.show', compact('truckrequest'));
    }

    public function update(Request $request, TruckRequest $truckrequest)
    {
        $data = $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:2000',
            // Optional: create truck while approving
            'create_truck' => 'sometimes|boolean',
            'truck_name' => 'required_if:create_truck,1|string|max:255',
            'license_plate' => 'nullable|string|max:50|unique:trucks,license_plate',
        ]);

        if ($truckrequest->status !== 'pending') {
            return back()->with('error','This request has already been processed.');
        }

        $truckrequest->admin_note = $data['admin_note'] ?? null;
        $truckrequest->handled_by = Auth::id();
        $truckrequest->handled_at = now();

        if ($data['action'] === 'approve') {
            $truckrequest->status = 'approved';
            // Optionally create and assign a new truck to the franchise
            if ($request->boolean('create_truck')) {
                Truck::create([
                    'name' => $data['truck_name'],
                    'license_plate' => $data['license_plate'] ?? null,
                    'franchise_id' => $truckrequest->franchise_id,
                    'ulid' => (string) Str::ulid(),
                ]);
            }
        } else {
            $truckrequest->status = 'rejected';
        }

        $truckrequest->save();
        return redirect()->route('admin.truckrequests.show', $truckrequest)
            ->with('success', 'Request processed.');
    }
}
