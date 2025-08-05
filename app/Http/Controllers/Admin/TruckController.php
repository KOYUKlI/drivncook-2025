<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use App\Models\Franchise;
use Illuminate\Http\Request;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $trucks = Truck::with('franchise')->get();
        return view('admin.trucks.index', compact('trucks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $franchises = Franchise::all();
        return view('admin.trucks.create', compact('franchises'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'franchise_id' => 'required|exists:franchises,id',
            'license_plate' => 'nullable'
        ]);
        Truck::create($request->only('name','franchise_id','license_plate'));
        return redirect()->route('admin.trucks.index')
                         ->with('success', 'Truck created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Truck $truck)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Truck $truck)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Truck $truck)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Truck $truck)
    {
        //
    }
}
