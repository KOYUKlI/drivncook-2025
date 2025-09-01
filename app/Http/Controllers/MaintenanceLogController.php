<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaintenanceLog\CancelMaintenanceRequest;
use App\Http\Requests\MaintenanceLog\CloseMaintenanceRequest;
use App\Http\Requests\MaintenanceLog\OpenMaintenanceRequest;
use App\Http\Requests\MaintenanceLog\PauseMaintenanceRequest;
use App\Http\Requests\MaintenanceLog\ResumeMaintenanceRequest;
use App\Http\Requests\MaintenanceLog\ScheduleMaintenanceRequest;
use App\Models\Deployment;
use App\Models\MaintenanceAttachment;
use App\Models\MaintenanceLog;
use App\Models\Truck;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MaintenanceLogController extends Controller
{
    /**
     * Display a listing of the maintenance logs.
     */
    public function index()
    {
        $this->authorize('viewAny', MaintenanceLog::class);
        
        $maintenanceLogs = MaintenanceLog::with(['truck', 'attachments'])
            ->orderByDesc('created_at')
            ->paginate(15);
            
        return view('maintenance.index', compact('maintenanceLogs'));
    }

    /**
     * Show the form for scheduling a new maintenance.
     */
    public function create()
    {
        $this->authorize('schedule', MaintenanceLog::class);
        
        $trucks = Truck::orderBy('identifier')->get();
        
        return view('maintenance.create', compact('trucks'));
    }

    /**
     * Schedule a new maintenance.
     */
    public function store(ScheduleMaintenanceRequest $request): RedirectResponse
    {
        // Check for conflicts with planned deployments
        $truck = Truck::findOrFail($request->truck_id);
        $conflicts = $this->checkDeploymentConflicts(
            $truck->id, 
            $request->planned_start_at, 
            $request->planned_end_at
        );
        
        if ($conflicts->isNotEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('maintenance.deployment_conflict'))
                ->with('conflicts', $conflicts);
        }
        
        DB::beginTransaction();
        
        try {
            // Create the maintenance log
            $maintenanceLog = MaintenanceLog::create([
                'truck_id' => $request->truck_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => MaintenanceLog::STATUS_PLANNED,
                'severity' => $request->severity,
                'priority' => $request->priority,
                'planned_start_at' => $request->planned_start_at,
                'planned_end_at' => $request->planned_end_at,
                'provider_name' => $request->provider_name,
                'provider_contact' => $request->provider_contact,
                'provider_reference' => $request->provider_reference,
                'estimated_cost_amount' => $request->estimated_cost_amount,
                'estimated_cost_currency' => $request->estimated_cost_currency,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
            
            // Handle attachments
            $this->handleAttachments($request, $maintenanceLog);
            
            DB::commit();
            
            return redirect()->route('maintenance.show', $maintenanceLog)
                ->with('success', __('maintenance.scheduled_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('maintenance.schedule_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified maintenance log.
     */
    public function show(MaintenanceLog $maintenanceLog)
    {
        $this->authorize('view', $maintenanceLog);
        
        $maintenanceLog->load(['truck', 'attachments', 'creator', 'updater']);
        
        return view('maintenance.show', compact('maintenanceLog'));
    }

    /**
     * Show the form for editing the specified maintenance log.
     */
    public function edit(MaintenanceLog $maintenanceLog)
    {
        $this->authorize('update', $maintenanceLog);
        
        $trucks = Truck::orderBy('identifier')->get();
        
        return view('maintenance.edit', compact('maintenanceLog', 'trucks'));
    }

    /**
     * Update the specified maintenance log.
     */
    public function update(ScheduleMaintenanceRequest $request, MaintenanceLog $maintenanceLog): RedirectResponse
    {
        // Can only update if in PLANNED status
        if ($maintenanceLog->status !== MaintenanceLog::STATUS_PLANNED) {
            return redirect()->route('maintenance.show', $maintenanceLog)
                ->with('error', __('maintenance.cannot_update_non_planned'));
        }
        
        // Check for conflicts with planned deployments
        $conflicts = $this->checkDeploymentConflicts(
            $request->truck_id, 
            $request->planned_start_at, 
            $request->planned_end_at,
            $maintenanceLog->id
        );
        
        if ($conflicts->isNotEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('maintenance.deployment_conflict'))
                ->with('conflicts', $conflicts);
        }
        
        DB::beginTransaction();
        
        try {
            // Update the maintenance log
            $maintenanceLog->update([
                'truck_id' => $request->truck_id,
                'title' => $request->title,
                'description' => $request->description,
                'severity' => $request->severity,
                'priority' => $request->priority,
                'planned_start_at' => $request->planned_start_at,
                'planned_end_at' => $request->planned_end_at,
                'provider_name' => $request->provider_name,
                'provider_contact' => $request->provider_contact,
                'provider_reference' => $request->provider_reference,
                'estimated_cost_amount' => $request->estimated_cost_amount,
                'estimated_cost_currency' => $request->estimated_cost_currency,
                'updated_by' => Auth::id(),
            ]);
            
            // Handle attachments
            $this->handleAttachments($request, $maintenanceLog);
            
            DB::commit();
            
            return redirect()->route('maintenance.show', $maintenanceLog)
                ->with('success', __('maintenance.updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('maintenance.update_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Open a maintenance.
     */
    public function open(OpenMaintenanceRequest $request, MaintenanceLog $maintenanceLog): RedirectResponse
    {
        // Check for conflicts with active deployments
        $conflicts = $this->checkActiveDeployments($maintenanceLog->truck_id);
        
        if ($conflicts->isNotEmpty()) {
            return redirect()->back()
                ->with('error', __('maintenance.active_deployment_conflict'))
                ->with('conflicts', $conflicts);
        }
        
        DB::beginTransaction();
        
        try {
            // Update the maintenance log
            $maintenanceLog->update([
                'status' => MaintenanceLog::STATUS_OPEN,
                'opened_at' => now(),
                'odometer_start' => $request->odometer_reading,
                'notes' => $request->notes,
                'updated_by' => Auth::id(),
            ]);
            
            // Handle attachments
            $this->handleAttachments($request, $maintenanceLog);
            
            DB::commit();
            
            return redirect()->route('maintenance.show', $maintenanceLog)
                ->with('success', __('maintenance.opened_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', __('maintenance.open_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Pause a maintenance.
     */
    public function pause(PauseMaintenanceRequest $request, MaintenanceLog $maintenanceLog): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            // Update the maintenance log
            $maintenanceLog->update([
                'status' => MaintenanceLog::STATUS_PAUSED,
                'paused_at' => now(),
                'pause_reason' => $request->pause_reason,
                'updated_by' => Auth::id(),
            ]);
            
            // Handle attachments
            $this->handleAttachments($request, $maintenanceLog);
            
            DB::commit();
            
            return redirect()->route('maintenance.show', $maintenanceLog)
                ->with('success', __('maintenance.paused_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', __('maintenance.pause_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Resume a maintenance.
     */
    public function resume(ResumeMaintenanceRequest $request, MaintenanceLog $maintenanceLog): RedirectResponse
    {
        // Check for conflicts with active deployments
        $conflicts = $this->checkActiveDeployments($maintenanceLog->truck_id);
        
        if ($conflicts->isNotEmpty()) {
            return redirect()->back()
                ->with('error', __('maintenance.active_deployment_conflict'))
                ->with('conflicts', $conflicts);
        }
        
        DB::beginTransaction();
        
        try {
            // Update the maintenance log
            $maintenanceLog->update([
                'status' => MaintenanceLog::STATUS_OPEN,
                'resume_notes' => $request->resume_notes,
                'updated_by' => Auth::id(),
            ]);
            
            // Handle attachments
            $this->handleAttachments($request, $maintenanceLog);
            
            DB::commit();
            
            return redirect()->route('maintenance.show', $maintenanceLog)
                ->with('success', __('maintenance.resumed_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', __('maintenance.resume_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Close a maintenance.
     */
    public function close(CloseMaintenanceRequest $request, MaintenanceLog $maintenanceLog): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            // Update the maintenance log
            $maintenanceLog->update([
                'status' => MaintenanceLog::STATUS_CLOSED,
                'closed_at' => now(),
                'resolution' => $request->resolution,
                'odometer_end' => $request->odometer_reading,
                'labor_cost_amount' => $request->labor_cost_amount,
                'labor_cost_currency' => $request->labor_cost_currency,
                'parts_cost_amount' => $request->parts_cost_amount,
                'parts_cost_currency' => $request->parts_cost_currency,
                'additional_costs_amount' => $request->additional_costs_amount,
                'additional_costs_currency' => $request->additional_costs_currency,
                'additional_costs_description' => $request->additional_costs_description,
                'provider_invoice_reference' => $request->provider_invoice_reference,
                'updated_by' => Auth::id(),
            ]);
            
            // Handle attachments
            $this->handleAttachments($request, $maintenanceLog);
            
            DB::commit();
            
            return redirect()->route('maintenance.show', $maintenanceLog)
                ->with('success', __('maintenance.closed_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', __('maintenance.close_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Cancel a maintenance.
     */
    public function cancel(CancelMaintenanceRequest $request, MaintenanceLog $maintenanceLog): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            // Update the maintenance log
            $maintenanceLog->update([
                'status' => MaintenanceLog::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
                'updated_by' => Auth::id(),
            ]);
            
            // Handle attachments
            $this->handleAttachments($request, $maintenanceLog);
            
            DB::commit();
            
            return redirect()->route('maintenance.show', $maintenanceLog)
                ->with('success', __('maintenance.cancelled_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', __('maintenance.cancel_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Download an attachment.
     */
    public function downloadAttachment(MaintenanceAttachment $attachment): BinaryFileResponse
    {
        $this->authorize('downloadAttachment', MaintenanceLog::class);
        
        if (!Storage::disk('private')->exists($attachment->path)) {
            abort(404);
        }
        
        return response()->file(
            Storage::disk('private')->path($attachment->path),
            ['Content-Disposition' => 'attachment; filename="' . $attachment->original_filename . '"']
        );
    }
    
    /**
     * Upload attachments to a maintenance log.
     */
    public function uploadAttachment(Request $request, MaintenanceLog $maintenanceLog): RedirectResponse
    {
        $this->authorize('addAttachment', $maintenanceLog);
        
        if (!$request->hasFile('attachments')) {
            return redirect()->back()
                ->with('error', __('maintenance.no_attachments_uploaded'));
        }
        
        DB::beginTransaction();
        
        try {
            $this->handleAttachments($request, $maintenanceLog);
            
            DB::commit();
            
            return redirect()->route('bo.maintenance.show', $maintenanceLog)
                ->with('success', __('maintenance.attachments_uploaded_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', __('maintenance.upload_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Handle attachment uploads.
     */
    private function handleAttachments(Request $request, MaintenanceLog $maintenanceLog): void
    {
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                $mimeType = $file->getMimeType();
                
                // Generate a unique filename
                $filename = (string) Str::ulid() . '.' . $extension;
                $path = 'maintenance/' . $maintenanceLog->id . '/' . $filename;
                
                // Store the file
                $file->storeAs('maintenance/' . $maintenanceLog->id, $filename, 'private');
                
                // Create the attachment record
                MaintenanceAttachment::create([
                    'maintenance_log_id' => $maintenanceLog->id,
                    'original_filename' => $originalFilename,
                    'path' => $path,
                    'mime_type' => $mimeType,
                    'size' => $size,
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }
    }

    /**
     * Check for conflicts with planned deployments.
     */
    private function checkDeploymentConflicts(
        int $truckId, 
        string $startDate, 
        string $endDate, 
        ?int $excludeMaintenanceId = null
    ) {
        // Check for conflicts with planned deployments
        return Deployment::query()
            ->where('truck_id', $truckId)
            ->where(function ($query) use ($startDate, $endDate) {
                // Deployment overlaps with maintenance period
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $startDate);
                });
            })
            ->where('status', '!=', 'cancelled')
            ->get();
    }

    /**
     * Check for active deployments.
     */
    private function checkActiveDeployments(int $truckId)
    {
        return Deployment::query()
            ->where('truck_id', $truckId)
            ->where(function ($query) {
                // Currently active deployment
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            })
            ->where('status', 'active')
            ->get();
    }
}
