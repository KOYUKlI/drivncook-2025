<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\CloseMaintenanceRequest;
use App\Http\Requests\OpenMaintenanceRequest;
use App\Models\MaintenanceLog;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Dompdf\Dompdf;
use Dompdf\Options;

class TruckMaintenanceController extends Controller
{
    public function open(Truck $truck, OpenMaintenanceRequest $request)
    {
        $this->authorize('create', MaintenanceLog::class);

        $data = $request->validated();

        $log = new MaintenanceLog();
        $log->truck_id = $truck->id;
        // Type/kind depending on schema
        if (Schema::hasColumn('maintenance_logs', 'type')) {
            $log->type = $data['type'];
        } elseif (Schema::hasColumn('maintenance_logs', 'kind')) {
            $log->kind = ucfirst($data['type']);
        }
        // Status column may not exist in legacy schema
        if (Schema::hasColumn('maintenance_logs', 'status')) {
            $log->status = 'open';
        }
        // Opened/start datetime depending on schema
        if (Schema::hasColumn('maintenance_logs', 'opened_at')) {
            $log->opened_at = $data['opened_at'];
        } elseif (Schema::hasColumn('maintenance_logs', 'started_at')) {
            $log->started_at = $data['opened_at'];
        }
        // Audit columns if available
        if (Schema::hasColumn('maintenance_logs', 'opened_by')) {
            $log->opened_by = Auth::id();
        }
        $log->description = $data['description'];
        if (Schema::hasColumn('maintenance_logs', 'cost_cents')) {
            $log->cost_cents = $data['cost_cents'] ?? null;
        }

        if ($request->hasFile('attachment') && Schema::hasColumn('maintenance_logs', 'attachment_path')) {
            $path = $request->file('attachment')->store("private/maintenance/{$truck->id}");
            $log->attachment_path = $path;
        }

        $log->save();

        return redirect()
            ->route('bo.trucks.show', $truck->id)
            ->with('success', __('ui.flash.maintenance_opened'));
    }

    public function close(string $log, CloseMaintenanceRequest $request)
    {
        $logModel = MaintenanceLog::findOrFail($log);
        $this->authorize('close', $logModel);
        // If status column exists, enforce transition; else rely on closed_at presence
        if (Schema::hasColumn('maintenance_logs', 'status')) {
            if ($logModel->status !== 'open') {
                return back()->with('error', __('ui.flash.invalid_transition'));
            }
        } elseif (!empty($logModel->closed_at)) {
            return back()->with('error', __('ui.flash.invalid_transition'));
        }

        $data = $request->validated();

        if (Schema::hasColumn('maintenance_logs', 'status')) {
            $logModel->status = 'closed';
        }
        $logModel->closed_at = $data['closed_at'];
        if (Schema::hasColumn('maintenance_logs', 'closed_by')) {
            $logModel->closed_by = Auth::id();
        }
        if (Schema::hasColumn('maintenance_logs', 'resolution')) {
            $logModel->resolution = $data['resolution'];
        }

        if ($request->hasFile('attachment') && Schema::hasColumn('maintenance_logs', 'attachment_path') && empty($logModel->attachment_path)) {
            $path = $request->file('attachment')->store("private/maintenance/{$logModel->truck_id}");
            $logModel->attachment_path = $path;
        }

        $logModel->save();

        return redirect()
            ->route('bo.trucks.show', $logModel->truck_id)
            ->with('success', __('ui.flash.maintenance_closed'));
    }

    public function download(string $log)
    {
        $logModel = MaintenanceLog::findOrFail($log);
        $this->authorize('view', $logModel);

    if (! Schema::hasColumn('maintenance_logs', 'attachment_path') || ! $logModel->attachment_path || ! Storage::disk('local')->exists($logModel->attachment_path)) {
            return back()->with('error', __('ui.flash.file_not_found'));
        }

        return response()->download(Storage::disk('local')->path($logModel->attachment_path));
    }

    /**
     * Export maintenance logs for a truck as CSV (UTF-8 BOM, ISO dates), with optional filters.
     */
    public function export(Truck $truck, Request $request): StreamedResponse
    {
        $this->authorize('view', $truck);

        $query = MaintenanceLog::query()->where('truck_id', $truck->id);

        // Filters: status, severity, from, to (by opened_at/started_at), default all
        if ($status = $request->string('status')->toString()) {
            if (Schema::hasColumn('maintenance_logs', 'status') && $status !== 'all') {
                $query->where('status', $status);
            }
        }
        if ($severity = $request->string('severity')->toString()) {
            if (Schema::hasColumn('maintenance_logs', 'severity') && $severity !== 'all') {
                $query->where('severity', $severity);
            }
        }
        $from = $request->date('from');
        $to = $request->date('to');
        if ($from || $to) {
            $dateCol = Schema::hasColumn('maintenance_logs', 'opened_at') ? 'opened_at' : (Schema::hasColumn('maintenance_logs', 'started_at') ? 'started_at' : null);
            if ($dateCol) {
                if ($from) {
                    $query->whereDate($dateCol, '>=', $from->format('Y-m-d'));
                }
                if ($to) {
                    $query->whereDate($dateCol, '<=', $to->format('Y-m-d'));
                }
            }
        }

        $logs = $query->orderByDesc(Schema::hasColumn('maintenance_logs', 'opened_at') ? 'opened_at' : (Schema::hasColumn('maintenance_logs', 'started_at') ? 'started_at' : 'created_at'))
            ->get();

        $filename = 'maintenance-'.$truck->id.'-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($logs) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM
            echo "\xEF\xBB\xBF";
            // Headers
            fputcsv($out, [
                'id','truck_id','type','status','severity','priority','opened_at','started_at','closed_at','due_at','cost_cents','description','resolution'
            ]);
            foreach ($logs as $log) {
                fputcsv($out, [
                    $log->id,
                    $log->truck_id,
                    $log->type ?? $log->kind ?? null,
                    $log->status ?? (empty($log->closed_at) ? 'open' : 'closed'),
                    $log->severity ?? null,
                    $log->priority ?? null,
                    optional($log->opened_at)->format('c'),
                    optional($log->started_at)->format('c'),
                    optional($log->closed_at)->format('c'),
                    optional($log->due_at)->format('c'),
                    $log->cost_cents ?? null,
                    $log->description,
                    $log->resolution ?? null,
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Generate a maintenance intervention PDF for a maintenance log (BO only).
     */
    public function interventionPdf(string $log)
    {
        $logModel = MaintenanceLog::with('truck')->findOrFail($log);
        $this->authorize('view', $logModel);

        $data = ['log' => $logModel];
        $html = view('pdfs.maintenance.intervention', $data)->render();

        $opts = new Options();
        $opts->set('isRemoteEnabled', true);
        $opts->set('isPhpEnabled', true);
        $opts->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($opts);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'maintenance-'.$logModel->id.'.pdf';
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
