<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRecord;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRecordController extends Controller
{
    public function index()
    {
        $franchiseId = Auth::user()->franchise_id;
        $records = MaintenanceRecord::whereHas('truck', fn($q)=>$q->where('franchise_id',$franchiseId))
            ->with('truck')->orderByDesc('maintenance_date')->get();
        return view('franchise.maintenance.index', compact('records'));
    }

    public function create()
    {
        $trucks = Truck::where('franchise_id', Auth::user()->franchise_id)->get();
        return view('franchise.maintenance.create', compact('trucks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'maintenance_date' => 'nullable|date',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);
        $truck = Truck::findOrFail($data['truck_id']);
        if ($truck->franchise_id !== Auth::user()->franchise_id) abort(403);
        MaintenanceRecord::create($data);
        return redirect()->route('franchise.maintenance.index')->with('success','Record created.');
    }

    public function edit(MaintenanceRecord $maintenance)
    {
        if ($maintenance->truck->franchise_id !== Auth::user()->franchise_id) abort(403);
        $trucks = Truck::where('franchise_id', Auth::user()->franchise_id)->get();
        return view('franchise.maintenance.edit', compact('maintenance','trucks'));
    }

    public function update(Request $request, MaintenanceRecord $maintenance)
    {
        if ($maintenance->truck->franchise_id !== Auth::user()->franchise_id) abort(403);
        $data = $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'maintenance_date' => 'nullable|date',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);
        $truck = Truck::findOrFail($data['truck_id']);
        if ($truck->franchise_id !== Auth::user()->franchise_id) abort(403);
        $maintenance->update($data);
        return redirect()->route('franchise.maintenance.index')->with('success','Record updated.');
    }

    public function destroy(MaintenanceRecord $maintenance)
    {
        if ($maintenance->truck->franchise_id !== Auth::user()->franchise_id) abort(403);
        $maintenance->delete();
        return redirect()->route('franchise.maintenance.index')->with('success','Record deleted.');
    }
}
