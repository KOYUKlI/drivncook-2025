<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $franchiseId = Auth::user()->franchise_id;
        if (empty($franchiseId)) {
            return redirect()->route('franchise.dashboard')
                ->with('error', "Your account isn't linked to any franchisee. Please contact an administrator.");
        }
        $trucks = Truck::where('franchise_id', $franchiseId)->get();
        return view('franchise.trucks.index', compact('trucks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (empty(Auth::user()->franchise_id)) {
            return redirect()->route('franchise.dashboard')
                ->with('error', "Your account isn't linked to any franchisee. Please contact an administrator.");
        }
        return view('franchise.trucks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        if (empty(Auth::user()->franchise_id)) {
            return redirect()->route('franchise.trucks.index')
                ->with('error', "Your account isn't linked to any franchisee. Please contact an administrator.");
        }
        // Normalize plate: empty -> null; trim whitespace
        $raw = trim((string) $request->input('license_plate', ''));
        $request->merge(['license_plate' => ($raw === '') ? null : $raw]);

        $request->validate([
            'name'          => 'required|string|max:255',
            'license_plate' => ['nullable','string','max:50','regex:/^[A-Za-z0-9\-\s]{1,15}$/','unique:trucks,license_plate']
        ]);
        $franchiseId = Auth::user()->franchise_id;
        $plate = $request->filled('license_plate') ? strtoupper($request->input('license_plate')) : null;
        Truck::create([
            'name'          => $request->name,
            'license_plate' => $plate,
            'franchise_id'  => $franchiseId
        ]);
    return redirect()->route('franchise.trucks.index')
             ->with('success', 'Truck added to your franchise.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Truck $truck)
    {
        if ($truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        return view('franchise.trucks.show', compact('truck'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Truck $truck)
    {
        if ($truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        return view('franchise.trucks.edit', compact('truck'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Truck $truck)
    {
        if ($truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        // Normalize plate: empty -> null; trim whitespace
        $raw = trim((string) $request->input('license_plate', ''));
        $request->merge(['license_plate' => ($raw === '') ? null : $raw]);

        $request->validate([
            'name'          => 'required|string|max:255',
            'license_plate' => ['nullable','string','max:50','regex:/^[A-Za-z0-9\-\s]{1,15}$/','unique:trucks,license_plate,'.$truck->id]
        ]);
        $plate = $request->filled('license_plate') ? strtoupper($request->input('license_plate')) : null;
        $truck->update([
            'name' => $request->input('name'),
            'license_plate' => $plate,
        ]);
    return redirect()->route('franchise.trucks.index')
             ->with('success', 'Truck updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Truck $truck)
    {
        if ($truck->franchise_id !== Auth::user()->franchise_id) {
            abort(403);
        }
        $truck->delete();
    return redirect()->route('franchise.trucks.index')
             ->with('success', 'Truck removed from your franchise.');
    }
}
