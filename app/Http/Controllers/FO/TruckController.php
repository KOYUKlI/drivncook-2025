<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Http\Requests\FO\MaintenanceRequestFormRequest;
use App\Models\MaintenanceLog;
use App\Models\Truck;
use App\Models\TruckDeployment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TruckController extends Controller
{
    /**
     * Display the truck details for the current franchisee.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        $franchisee = Auth::user()->franchisee;

        if (!$franchisee) {
            return redirect()->route('fo.dashboard')
                ->with('error', __('ui.fo.truck.messages.no_franchisee'));
        }

        // Get the truck assigned to the current franchisee
        $truck = Truck::whereHas('ownerships', function ($query) use ($franchisee) {
            $query->where('franchisee_id', $franchisee->id)
                ->whereNull('ended_at');
        })->first();

        if (!$truck) {
            return redirect()->route('fo.dashboard')
                ->with('error', __('ui.fo.truck.messages.no_truck_assigned'));
        }

        // Get upcoming deployments
        $upcomingDeployments = TruckDeployment::where('truck_id', $truck->id)
            ->where(function ($query) {
                $query->whereNull('actual_end_at')
                    ->orWhere('planned_start_at', '>', now());
            })
            ->orderBy('planned_start_at')
            ->take(5)
            ->get();

        // Get maintenance history
        $maintenanceLogs = MaintenanceLog::where('truck_id', $truck->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('fo.truck.show', compact('truck', 'upcomingDeployments', 'maintenanceLogs'));
    }

    /**
     * Store a new maintenance request.
     *
     * @param  \App\Http\Requests\FO\MaintenanceRequestFormRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestMaintenance(MaintenanceRequestFormRequest $request)
    {
        $franchisee = Auth::user()->franchisee;

        if (!$franchisee) {
            return redirect()->route('fo.dashboard')
                ->with('error', __('ui.fo.truck.messages.no_franchisee'));
        }

        // Get the truck assigned to the current franchisee
        $truck = Truck::whereHas('ownerships', function ($query) use ($franchisee) {
            $query->where('franchisee_id', $franchisee->id)
                ->whereNull('ended_at');
        })->first();

        if (!$truck) {
            return redirect()->route('fo.dashboard')
                ->with('error', __('ui.fo.truck.messages.no_truck_assigned'));
        }

        // Create a new maintenance log
        $maintenanceLog = new MaintenanceLog();
        $maintenanceLog->truck_id = $truck->id;
        $maintenanceLog->title = $request->title;
        $maintenanceLog->description = $request->description;
        $maintenanceLog->type = $request->type;
        $maintenanceLog->status = 'planned';
        $maintenanceLog->source = 'fo';
        $maintenanceLog->save();

        // Handle file upload if provided
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('maintenance/' . $maintenanceLog->id, 'private');
            
            $attachment = new \App\Models\MaintenanceAttachment();
            $attachment->maintenance_log_id = $maintenanceLog->id;
            $attachment->file_path = $path;
            $attachment->file_name = $file->getClientOriginalName();
            $attachment->file_size = $file->getSize();
            $attachment->file_type = $file->getMimeType();
            $attachment->save();
        }

        return redirect()->route('fo.truck.show')
            ->with('success', __('ui.fo.maintenance_request.messages.created'));
    }
}
