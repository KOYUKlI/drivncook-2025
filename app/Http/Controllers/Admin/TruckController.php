<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $trucks = Truck::with('franchise')->get();
    // Backfill missing ULIDs defensively (in case legacy rows missed migration)
    $trucks->filter(fn($t) => empty($t->ulid))
           ->each(function($t){ $t->ulid = (string) Str::ulid(); $t->save(); });
        return view('admin.trucks.index', compact('trucks'));
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
    {
        $franchises = Franchise::all();  // besoin de choisir la franchise associée
        return view('admin.trucks.create', compact('franchises'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'license_plate'=> 'nullable|string|max:50|unique:trucks,license_plate',
            'franchise_id' => 'required|exists:franchises,id'
        ]);
        Truck::create($request->only('name', 'license_plate', 'franchise_id'));
        return redirect()->route('admin.trucks.index')
                         ->with('success', 'Truck created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Truck $truck)
    {
        // $truck est récupéré via route-model binding
        return view('admin.trucks.show', compact('truck'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Truck $truck)
    {
        $franchises = Franchise::all();
        return view('admin.trucks.edit', compact('truck', 'franchises'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Truck $truck)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'license_plate'=> 'nullable|string|max:50|unique:trucks,license_plate,'.$truck->id,
            'franchise_id' => 'required|exists:franchises,id'
        ]);
        $truck->update($request->only('name', 'license_plate', 'franchise_id'));
        return redirect()->route('admin.trucks.index')
                         ->with('success', 'Truck updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Truck $truck)
    {
        $truck->delete();
        return redirect()->route('admin.trucks.index')
                         ->with('success', 'Truck deleted successfully.');
    }
}
