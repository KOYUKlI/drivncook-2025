<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use App\Models\TruckDeployment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TruckDeploymentCalendarController extends Controller
{
    /**
     * Display the calendar view for truck deployments
     */
    public function index(Truck $truck)
    {
        $this->authorize('viewAny', TruckDeployment::class);
        
        // Get all deployments for this truck
        $deployments = TruckDeployment::where('truck_id', $truck->id)
            ->with('franchisee')
            ->orderBy('planned_start_at')
            ->get()
            ->map(function ($deployment) {
                return [
                    'id' => $deployment->id,
                    'title' => $deployment->location_text,
                    'start' => $deployment->planned_start_at->toIso8601String(),
                    'end' => $deployment->planned_end_at->toIso8601String(),
                    'allDay' => false,
                    'extendedProps' => [
                        'status' => $deployment->status,
                        'franchisee' => $deployment->franchisee?->name,
                        'notes' => $deployment->notes,
                    ]
                ];
            });
        
        // Get upcoming deployments (next 7 days)
        $upcomingDeployments = TruckDeployment::where('truck_id', $truck->id)
            ->where('planned_start_at', '>=', now())
            ->where('planned_start_at', '<=', now()->addDays(7))
            ->where('status', '!=', 'cancelled')
            ->orderBy('planned_start_at')
            ->limit(5)
            ->get();
        
        // Calculate statistics
        $stats = $this->calculateStats($truck);
        
        return view('bo.trucks.deployments.calendar', [
            'truck' => $truck,
            'deployments' => $deployments,
            'upcomingDeployments' => $upcomingDeployments,
            'stats' => $stats,
        ]);
    }
    
    /**
     * Calculate statistics for the truck's deployments
     */
    private function calculateStats(Truck $truck)
    {
        $deployments = TruckDeployment::where('truck_id', $truck->id)->get();
        
        $total = $deployments->count();
        $active = $deployments->where('status', 'open')->count();
        $completed = $deployments->where('status', 'closed')->count();
        $cancelled = $deployments->where('status', 'cancelled')->count();
        
        // Calculate utilization (% of time deployed in last 30 days)
        $utilization = TruckDeployment::calculateUtilization($truck->id);
        
        // Get popular locations
        $locations = TruckDeployment::where('truck_id', $truck->id)
            ->select('location_text', DB::raw('count(*) as count'))
            ->groupBy('location_text')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($location) use ($total) {
                return [
                    'name' => $location->location_text,
                    'count' => $location->count,
                    'percentage' => $total > 0 ? ($location->count / $total) * 100 : 0
                ];
            });
        
        return [
            'total' => $total,
            'active' => $active,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'utilization' => $utilization,
            'locations' => $locations,
        ];
    }
}
