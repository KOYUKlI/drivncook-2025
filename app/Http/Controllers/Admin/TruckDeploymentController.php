<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use App\Models\Location;
use App\Models\TruckDeployment;
use Illuminate\Http\Request;

class TruckDeploymentController extends Controller
{
    public function index() {
        $deployments = TruckDeployment::with(['truck','location'])->latest('starts_at')->paginate(20);
        return view('admin.deployments.index', compact('deployments'));
    }
    public function create() {
        return view('admin.deployments.create', [
            'trucks' => Truck::orderBy('name')->get(),
            'locations' => Location::orderBy('label')->get(),
        ]);
    }
    public function store(Request $request) {
        $data = $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'location_id' => 'required|exists:locations,id',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);
        TruckDeployment::create($data);
        return redirect()->route('admin.deployments.index')->with('success','Deployment created');
    }
    public function show(TruckDeployment $deployment) { return view('admin.deployments.show', compact('deployment')); }
    public function edit(TruckDeployment $deployment) {
        return view('admin.deployments.edit', [
            'deployment' => $deployment,
            'trucks' => Truck::orderBy('name')->get(),
            'locations' => Location::orderBy('label')->get(),
        ]);
    }
    public function update(Request $request, TruckDeployment $deployment) {
        $data = $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'location_id' => 'required|exists:locations,id',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);
        $deployment->update($data);
        return redirect()->route('admin.deployments.index')->with('success','Deployment updated');
    }
    public function destroy(TruckDeployment $deployment) { $deployment->delete(); return redirect()->route('admin.deployments.index')->with('success','Deployment deleted'); }
}
