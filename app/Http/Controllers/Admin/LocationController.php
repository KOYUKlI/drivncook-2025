<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index() { $locations = Location::latest()->paginate(20); return view('admin.locations.index', compact('locations')); }
    public function create() { return view('admin.locations.create'); }
    public function store(Request $request) {
        $data = $request->validate(['label' => 'required|string|max:255','address' => 'nullable|string','city'=>'nullable|string','postal_code'=>'nullable|string|max:20','lat'=>'nullable|numeric','lng'=>'nullable|numeric']);
        Location::create($data); return redirect()->route('admin.locations.index')->with('success','Location created'); }
    public function show(Location $location) { return view('admin.locations.show', compact('location')); }
    public function edit(Location $location) { return view('admin.locations.edit', compact('location')); }
    public function update(Request $request, Location $location) {
        $data = $request->validate(['label' => 'required|string|max:255','address' => 'nullable|string','city'=>'nullable|string','postal_code'=>'nullable|string|max:20','lat'=>'nullable|numeric','lng'=>'nullable|numeric']);
        $location->update($data); return redirect()->route('admin.locations.index')->with('success','Location updated'); }
    public function destroy(Location $location) { $location->delete(); return redirect()->route('admin.locations.index')->with('success','Location deleted'); }
}
