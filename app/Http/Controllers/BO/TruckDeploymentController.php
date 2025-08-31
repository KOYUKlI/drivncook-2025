<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\TruckDeployment\ScheduleDeploymentRequest;
use App\Http\Requests\TruckDeployment\OpenDeploymentRequest;
use App\Http\Requests\TruckDeployment\CloseDeploymentRequest;
use App\Http\Requests\TruckDeployment\CancelDeploymentRequest;
use App\Http\Requests\TruckDeployment\RescheduleDeploymentRequest;
use App\Models\Truck;
use App\Models\TruckDeployment;
use App\Models\Franchisee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class TruckDeploymentController extends Controller
{
    /**
     * Get all deployments for a truck with optional filtering
     */
    public function getDeploymentsByTruck(Truck $truck, Request $request)
    {
        $this->authorize('viewAny', TruckDeployment::class);
        
        $query = TruckDeployment::query()
            ->where('truck_id', $truck->id)
            ->orderBy('planned_start_at', 'desc');
            
        // Apply filters
        if ($request->has('status') && !empty($request->status)) {
            $query->whereIn('status', (array)$request->status);
        }
        
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->where('planned_start_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->where('planned_end_at', '<=', $request->end_date);
        }
        
        if ($request->has('location') && !empty($request->location)) {
            $query->where('location_text', 'like', '%' . $request->location . '%');
        }
        
        if ($request->has('franchisee_id') && !empty($request->franchisee_id)) {
            $query->where('franchisee_id', $request->franchisee_id);
        }
        
        return $query->paginate(10);
    }
    
    /**
     * Schedule a new deployment
     */
    public function schedule(Truck $truck, ScheduleDeploymentRequest $request)
    {
        $this->authorize('create', TruckDeployment::class);

        $data = $request->validated();

        $deployment = TruckDeployment::create([
            'id' => (string) Str::ulid(),
            'truck_id' => $truck->id,
            'franchisee_id' => $data['franchisee_id'] ?? null,
            'location_text' => $data['location_text'],
            'planned_start_at' => $data['planned_start_at'] ?? null,
            'planned_end_at' => $data['planned_end_at'] ?? null,
            'status' => TruckDeployment::STATUS_PLANNED,
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', __('deployment.messages.scheduled'));
    }
    
    /**
     * Reschedule an existing deployment
     */
    public function reschedule(TruckDeployment $deployment, RescheduleDeploymentRequest $request)
    {
        $this->authorize('reschedule', $deployment);

        if ($deployment->status !== TruckDeployment::STATUS_PLANNED) {
            return response()->json(['message' => __('deployment.errors.invalid_transition')], Response::HTTP_CONFLICT);
        }

        $data = $request->validated();
        
        $deployment->planned_start_at = $data['planned_start_at'];
        $deployment->planned_end_at = $data['planned_end_at'];
        
        if (isset($data['location_text'])) {
            $deployment->location_text = $data['location_text'];
        }
        
        if (isset($data['notes'])) {
            $deployment->notes = $data['notes'];
        }
        
        $deployment->save();

        return back()->with('success', __('deployment.messages.rescheduled'));
    }

    /**
     * Open/start a deployment
     */
    public function open(TruckDeployment $deployment, OpenDeploymentRequest $request)
    {
        $this->authorize('open', $deployment);

        if ($deployment->status !== TruckDeployment::STATUS_PLANNED) {
            $message = __('deployment.errors.invalid_transition');
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], Response::HTTP_CONFLICT);
            }
            
            return back()->withErrors(['deployment' => $message]);
        }

        $data = $request->validated();
        $deployment->status = TruckDeployment::STATUS_OPEN;
        $deployment->actual_start_at = $data['actual_start_at'];
        
        if (isset($data['location_text'])) {
            $deployment->location_text = $data['location_text'];
        }
        
        $deployment->save();

        $message = __('deployment.messages.opened');
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'deployment' => $deployment->fresh()
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Close/end a deployment
     */
    public function close(TruckDeployment $deployment, CloseDeploymentRequest $request)
    {
        $this->authorize('close', $deployment);

        if ($deployment->status !== TruckDeployment::STATUS_OPEN) {
            $message = __('deployment.errors.invalid_transition');
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], Response::HTTP_CONFLICT);
            }
            
            return back()->withErrors(['deployment' => $message]);
        }

        $data = $request->validated();
        $deployment->status = TruckDeployment::STATUS_CLOSED;
        $deployment->actual_end_at = $data['actual_end_at'];
        
        // Persist actual_start_at if posted (for validation check continuity)
        if (!$deployment->actual_start_at && !empty($data['actual_start_at'])) {
            $deployment->actual_start_at = $data['actual_start_at'];
        }
        
        if (isset($data['notes'])) {
            $deployment->notes = $data['notes'];
        }
        
        $deployment->save();

        $message = __('deployment.messages.closed');
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'deployment' => $deployment->fresh()
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Cancel a deployment
     */
    public function cancel(TruckDeployment $deployment, CancelDeploymentRequest $request)
    {
        $this->authorize('cancel', $deployment);

        if (!in_array($deployment->status, [TruckDeployment::STATUS_PLANNED, TruckDeployment::STATUS_OPEN])) {
            $message = __('deployment.errors.invalid_transition');
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], Response::HTTP_CONFLICT);
            }
            
            return back()->withErrors(['deployment' => $message]);
        }

        $data = $request->validated();
        $deployment->status = TruckDeployment::STATUS_CANCELLED;
        $deployment->cancel_reason = $data['cancel_reason'] ?? null;
        $deployment->save();

        $message = __('deployment.messages.cancelled');
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'deployment' => $deployment->fresh()
            ]);
        }

        return back()->with('success', $message);
    }
    
    /**
     * Export deployments to CSV
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', TruckDeployment::class);
        
        $query = TruckDeployment::query()
            ->with(['truck', 'franchisee'])
            ->orderBy('planned_start_at', 'desc');
            
        // Apply filters (similar to getDeploymentsByTruck)
        if ($request->has('truck_id') && !empty($request->truck_id)) {
            $query->where('truck_id', $request->truck_id);
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $query->whereIn('status', (array)$request->status);
        }
        
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->where('planned_start_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->where('planned_end_at', '<=', $request->end_date);
        }
        
        $deployments = $query->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="deployments.csv"',
        ];
        
        $columns = [
            'ID', 'Truck', 'Franchisee', 'Location', 'Status',
            'Planned Start', 'Planned End', 'Actual Start', 'Actual End',
            'Notes', 'Cancel Reason', 'Created At'
        ];
        
        $callback = function() use ($deployments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($deployments as $deployment) {
                fputcsv($file, [
                    $deployment->id,
                    $deployment->truck->registration_number ?? 'N/A',
                    $deployment->franchisee->name ?? 'N/A',
                    $deployment->location_text,
                    $deployment->status,
                    $deployment->planned_start_at?->format('Y-m-d H:i') ?? 'N/A',
                    $deployment->planned_end_at?->format('Y-m-d H:i') ?? 'N/A',
                    $deployment->actual_start_at?->format('Y-m-d H:i') ?? 'N/A',
                    $deployment->actual_end_at?->format('Y-m-d H:i') ?? 'N/A',
                    $deployment->notes,
                    $deployment->cancel_reason,
                    $deployment->created_at->format('Y-m-d H:i'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
