<?php

namespace App\Http\Controllers\FO;

use App\Http\Controllers\Controller;
use App\Http\Requests\FO\MaintenanceRequestFormRequest;
use App\Models\MaintenanceAttachment;
use App\Models\MaintenanceLog;
use App\Models\Truck;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MaintenanceController extends Controller
{
    /**
     * List maintenance logs for the current franchisee's truck.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $franchisee = Auth::user()->franchisee;
        if (!$franchisee) {
            return redirect()->route('fo.dashboard')
                ->with('error', __('ui.fo.truck.messages.no_franchisee'));
        }

        $truck = Truck::whereHas('ownerships', function ($q) use ($franchisee) {
            $q->where('franchisee_id', $franchisee->id)->whereNull('ended_at');
        })->first();

        if (!$truck) {
            return redirect()->route('fo.dashboard')
                ->with('error', __('ui.fo.truck.messages.no_truck_assigned'));
        }

        $query = MaintenanceLog::with('attachments')
            ->where('truck_id', $truck->id)
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('fo.maintenance.index', compact('truck', 'logs'));
    }

    /**
     * Store a new maintenance request from FO.
     */
    public function store(MaintenanceRequestFormRequest $request): RedirectResponse
    {
        $franchisee = Auth::user()->franchisee;
        if (!$franchisee) {
            return redirect()->route('fo.dashboard')
                ->with('error', __('ui.fo.truck.messages.no_franchisee'));
        }

        $truck = Truck::whereHas('ownerships', function ($q) use ($franchisee) {
            $q->where('franchisee_id', $franchisee->id)->whereNull('ended_at');
        })->first();

        if (!$truck) {
            return redirect()->route('fo.dashboard')
                ->with('error', __('ui.fo.truck.messages.no_truck_assigned'));
        }

        $log = new MaintenanceLog();
        $log->truck_id = $truck->id;
        $log->title = $request->title;
        $log->description = $request->description;
        // DB uses legacy 'kind' column; map preventive/corrective accordingly
        $log->kind = $request->type === 'preventive' ? 'Preventive' : 'Corrective';
        $log->status = MaintenanceLog::STATUS_PLANNED;
        $log->save();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = $file->getClientOriginalName();
            $path = $file->store('maintenance/' . $log->id, 'private');

            MaintenanceAttachment::create([
                'maintenance_log_id' => $log->id,
                'label' => $filename,
                'path' => $path,
                'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
                'size_bytes' => $file->getSize(),
                'uploaded_by' => Auth::id(),
            ]);
        }

        return redirect()->route('fo.maintenance.index')
            ->with('success', __('ui.fo.maintenance_request.messages.created'));
    }

    /**
     * Show a maintenance log details (FO-scoped).
     */
    public function show(MaintenanceLog $maintenanceLog): View|RedirectResponse
    {
        // Authorize via policy which includes franchisee scoping
        $this->authorize('view', $maintenanceLog);

        $maintenanceLog->load(['attachments', 'truck']);
        return view('fo.maintenance.show', compact('maintenanceLog'));
    }

    /**
     * Download an attachment if the user can view the related log.
     */
    public function download(MaintenanceLog $maintenanceLog, MaintenanceAttachment $attachment): BinaryFileResponse
    {
        // Ensure the attachment belongs to the log
        abort_if($attachment->maintenance_log_id !== $maintenanceLog->id, 404);
        // Enforce FO scoping
        $this->authorize('view', $maintenanceLog);

        if (!Storage::disk('private')->exists($attachment->path)) {
            abort(404);
        }

        return response()->download(
            Storage::disk('private')->path($attachment->path),
            $attachment->label ?: basename($attachment->path)
        );
    }
}
