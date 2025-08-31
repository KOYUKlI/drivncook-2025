<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTruckRequest;
use App\Http\Requests\UpdateTruckRequest;
use App\Http\Requests\UpdateTruckStatusRequest;
use App\Http\Requests\ScheduleTruckDeploymentRequest;
use App\Models\Deployment;
use App\Models\Franchisee;
use App\Models\MaintenanceLog;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class TruckController extends Controller
{
    /**
     * Show the form to create a new truck.
     */
    public function create()
    {
        $this->authorize('create', Truck::class);

        $franchisees = \App\Models\Franchisee::select('id', 'name')->orderBy('name')->get();

        return view('bo.trucks.create', compact('franchisees'));
    }
    
    /**
     * Store a newly created truck in storage.
     */
    public function store(StoreTruckRequest $request)
    {
        $this->authorize('create', Truck::class);

        $data = $request->validated();
        
        // Create truck with ULID
        $truck = new Truck();
        $truck->id = (string) Str::ulid();
        
        // Generate code (TRK- followed by last 4 chars of ULID)
        $lastPart = substr($truck->id, -4);
        $truck->code = "TRK-{$lastPart}";
        
        // Map UI fields to DB columns
        $truck->name = $data['name'];
        $truck->plate = $data['plate_number'];
        $truck->vin = $data['vin'] ?? null;
        $truck->make = $data['make'] ?? null;
        $truck->model = $data['model'] ?? null;
        $truck->year = $data['year'] ?? null;
        $truck->mileage_km = $data['mileage_km'] ?? null;
        $truck->franchisee_id = $data['franchisee_id'] ?? null;
        $truck->notes = $data['notes'] ?? null;
        
        // Map UI status to enum
        $statusMap = [
            'draft' => 'Draft',
            'active' => 'Active',
            'in_maintenance' => 'InMaintenance',
            'retired' => 'Retired',
        ];
        $truck->status = $statusMap[$data['status']] ?? 'Draft';
        
        // Set dates if provided
        if (!empty($data['acquired_at'])) {
            $truck->acquired_at = $data['acquired_at'];
        }
        
        if (!empty($data['commissioned_at'])) {
            $truck->service_start = $data['commissioned_at'];
        }
        
        $truck->save();
        
        // Handle document uploads
        if ($request->hasFile('registration_doc')) {
            $path = $request->file('registration_doc')->store('private/trucks/' . $truck->id);
            $truck->registration_doc_path = $path;
            $truck->save();
        }
        
        if ($request->hasFile('insurance_doc')) {
            $path = $request->file('insurance_doc')->store('private/trucks/' . $truck->id);
            $truck->insurance_doc_path = $path;
            $truck->save();
        }

        return redirect()
            ->route('bo.trucks.show', $truck->id)
            ->with('success', __('ui.flash.created'));
    }

    /**
     * Show the form to edit an existing truck.
     */
    public function edit(string $id)
    {
        $truck = Truck::findOrFail($id);
        $this->authorize('update', $truck);

        $franchisees = \App\Models\Franchisee::select('id', 'name')->orderBy('name')->get();

        return view('bo.trucks.edit', compact('truck', 'franchisees'));
    }

    /**
     * Update the specified truck.
     */
    public function update(UpdateTruckRequest $request, string $id)
    {
        $truck = Truck::findOrFail($id);
        $this->authorize('update', $truck);

        $data = $request->validated();

        // Map UI fields to DB columns
        $truck->name = $data['name'];
        $truck->plate = $data['plate_number'];
        $truck->vin = $data['vin'] ?? null;
        $truck->make = $data['make'] ?? null;
        $truck->model = $data['model'] ?? null;
        $truck->year = $data['year'] ?? null;
        $truck->mileage_km = $data['mileage_km'] ?? null;
        $truck->franchisee_id = $data['franchisee_id'] ?? null;
        $truck->notes = $data['notes'] ?? null;
        
        // Map UI status to enum
        $statusMap = [
            'draft' => 'Draft',
            'active' => 'Active',
            'in_maintenance' => 'InMaintenance',
            'retired' => 'Retired',
        ];
        $truck->status = $statusMap[$data['status']] ?? 'Draft';

        // Handle document uploads and archiving
        if ($request->hasFile('registration_doc')) {
            // Archive old document if exists
            if ($truck->registration_doc_path) {
                $oldPath = $truck->registration_doc_path;
                $archivePath = 'private/trucks/' . $truck->id . '/archive/' . basename($oldPath) . '.' . now()->timestamp;
                Storage::disk('local')->copy($oldPath, $archivePath);
            }
            
            // Store new document
            $path = $request->file('registration_doc')->store('private/trucks/' . $truck->id);
            $truck->registration_doc_path = $path;
        }

        if ($request->hasFile('insurance_doc')) {
            // Archive old document if exists
            if ($truck->insurance_doc_path) {
                $oldPath = $truck->insurance_doc_path;
                $archivePath = 'private/trucks/' . $truck->id . '/archive/' . basename($oldPath) . '.' . now()->timestamp;
                Storage::disk('local')->copy($oldPath, $archivePath);
            }
            
            // Store new document
            $path = $request->file('insurance_doc')->store('private/trucks/' . $truck->id);
            $truck->insurance_doc_path = $path;
        }

        $truck->save();

        return redirect()
            ->route('bo.trucks.show', $truck->id)
            ->with('success', __('ui.flash.updated'));
    }
    /**
     * Calculate statistics
     */
    private function calculateStatistics()
    {
        $allTrucks = Truck::all();
        return [
            'total' => $allTrucks->count(),
            'active' => $allTrucks->where('status', 'Active')->count(),
            'maintenance' => $allTrucks->where('status', 'InMaintenance')->count(),
            'inactive' => $allTrucks->where('status', 'Retired')->count(),
        ];
    }

    /**
     * Display a listing of trucks with status filtering.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        // Match Blade filter name
        $franchiseeId = $request->input('franchisee_id', 'all');
        $search = $request->input('search');

        $query = Truck::query()->with('franchisee');

        // Filter trucks based on status - map frontend values to enum values
        if ($status !== 'all') {
            // Accept the same keys used in the view filters
            $statusMap = [
                'active' => 'Active',
                'in_maintenance' => 'InMaintenance',
                'retired' => 'Retired',
                'pending' => 'Draft',
            ];

            if (isset($statusMap[$status])) {
                $query->where('status', $statusMap[$status]);
            }
        }

        // Filter by franchisee
        if ($franchiseeId !== 'all') {
            $query->where('franchisee_id', $franchiseeId);
        }

        // Search by truck code/plate
        if ($search) {
            $query->where('plate', 'like', "%{$search}%");
        }

        $trucks = $query->get()->map(function ($truck) {
            // Map status enum values to frontend expected values (align with translations)
            $statusMap = [
                'Active' => 'active',
                'InMaintenance' => 'in_maintenance',
                'Retired' => 'retired',
                'Draft' => 'pending',
            ];

            // Handle legacy/new maintenance schemas
            if (Schema::hasColumn('maintenance_logs', 'opened_at')) {
                $lastMaintenance = $truck->maintenanceLogs()->latest('opened_at')->first()?->opened_at?->format('Y-m-d');
                $nextMaintenance = $truck->maintenanceLogs()
                    ->where('opened_at', '>', now())
                    ->orderBy('opened_at')
                    ->first()?->opened_at?->format('Y-m-d');
            } else {
                $lastMaintenance = $truck->maintenanceLogs()->latest('started_at')->first()?->started_at?->format('Y-m-d');
                $nextMaintenance = $truck->maintenanceLogs()
                    ->where('started_at', '>', now())
                    ->orderBy('started_at')
                    ->first()?->started_at?->format('Y-m-d');
            }

            return [
                'id' => $truck->id,
                'code' => $truck->plate, // use plate as code
                'status' => $statusMap[$truck->status] ?? 'inactive',
                'franchisee' => $truck->franchisee->name ?? __('ui.bo.trucks.unassigned'),
                'last_maintenance' => $lastMaintenance,
                'next_maintenance' => $nextMaintenance,
            ];
        })->toArray();

        // Get franchisees for filter
        $franchisees = \App\Models\Franchisee::select('id', 'name')->get();

        // Calculate statistics
        $stats = $this->calculateStatistics();

        return view('bo.trucks.index', compact('trucks', 'stats', 'status', 'franchisees', 'franchiseeId', 'search'));
    }

    /**
     * Display the specified truck with tabs (deployments, maintenance).
     */
    public function show(string $id)
    {
        $truck = Truck::with(['franchisee', 'deployments', 'maintenanceLogs' => function ($q) {
            if (Schema::hasColumn('maintenance_logs', 'opened_at')) {
                $q->orderByDesc('opened_at');
            } else {
                $q->orderByDesc('started_at');
            }
        }])->findOrFail($id);

        // Transform truck data to expected format
        $statusMap = [
            'Active' => 'active',
            'InMaintenance' => 'in_maintenance',
            'Retired' => 'retired',
            'Draft' => 'pending',
        ];

        $truckData = [
            'id' => $truck->id,
            'code' => $truck->plate,
            'status' => $statusMap[$truck->status] ?? 'inactive',
            'franchisee' => $truck->franchisee->name ?? __('ui.bo.trucks.unassigned'),
            'franchisee_email' => $truck->franchisee->email ?? __('ui.bo.trucks.not_provided'),
            'has_registration' => !empty($truck->registration_doc_path),
            'has_insurance' => !empty($truck->insurance_doc_path),
            'model' => 'N/A', // TODO: Add model field
            'license_plate' => $truck->plate,
            'purchase_date' => $truck->service_start?->format('Y-m-d'),
            'warranty_end' => 'N/A', // TODO: Add warranty field
            'deployments' => $truck->deployments->map(function ($d) {
                $usingNew = Schema::hasTable('truck_deployments');
                if ($usingNew) {
                    $status = $d->status ?? 'planned';
                    return [
                        'id' => $d->id,
                        'location' => $d->location_text,
                        'planned_start_at' => $d->planned_start_at?->format('Y-m-d H:i'),
                        'planned_end_at' => $d->planned_end_at?->format('Y-m-d H:i'),
                        'actual_start_at' => $d->actual_start_at?->format('Y-m-d H:i'),
                        'actual_end_at' => $d->actual_end_at?->format('Y-m-d H:i'),
                        'franchisee' => optional($d->franchisee)->name,
                        'status' => $status,
                    ];
                }
                // Legacy mapping
                $status = $d->end_date ? 'closed' : 'planned';
                return [
                    'id' => $d->id,
                    'location' => $d->location,
                    'planned_start_at' => $d->start_date?->format('Y-m-d H:i'),
                    'planned_end_at' => $d->end_date?->format('Y-m-d H:i'),
                    'actual_start_at' => null,
                    'actual_end_at' => null,
                    'franchisee' => optional($d->franchisee)->name ?? null,
                    'status' => $status,
                ];
            })->toArray(),
            'maintenance' => $truck->maintenanceLogs->map(function ($log) {
                $openedRaw = $log->opened_at ?? $log->started_at ?? null;
                $typeRaw = $log->type ?? $log->kind ?? null;
                $typeKey = $typeRaw ? strtolower($typeRaw) : null;
                $status = $log->status ?? (empty($log->closed_at) ? 'open' : 'closed');

                return [
                    'id' => $log->id,
                    'opened_at' => $openedRaw ? Carbon::parse($openedRaw)->format('Y-m-d H:i') : null,
                    'closed_at' => $log->closed_at ? Carbon::parse($log->closed_at)->format('Y-m-d H:i') : null,
                    'type' => $typeKey,
                    'status' => $status,
                    'cost' => $log->cost_cents ?? null,
                    'has_attachment' => !empty($log->attachment_path),
                    'description' => $log->description,
                    'resolution' => $log->resolution ?? null,
                ];
            })->toArray(),
        ];

        // Calculate status counts for trucks (secure with ?? 0)
        $statusCounts = [
            'active' => Truck::where('status', 'Active')->count() ?? 0,
            'in_maintenance' => Truck::where('status', 'InMaintenance')->count() ?? 0,
            'retired' => Truck::where('status', 'Retired')->count() ?? 0,
            'pending' => Truck::where('status', 'Draft')->count() ?? 0,
        ];

        // Utilization last 30 days
        $since = now()->subDays(30);
        $seconds = 0;
        foreach ($truck->deployments as $d) {
            $usingNew = Schema::hasTable('truck_deployments');
            // Ignore cancelled deployments for utilization
            if ($usingNew && isset($d->status) && $d->status === 'cancelled') {
                continue;
            }
            $start = $usingNew ? ($d->actual_start_at ?? $d->planned_start_at) : $d->start_date;
            $end = $usingNew ? ($d->actual_end_at ?? $d->planned_end_at) : $d->end_date;
            if ($start) {
                $startC = \Carbon\Carbon::parse($start);
                $endC = $end ? \Carbon\Carbon::parse($end) : now();
                if ($endC->lessThan($since)) { continue; }
                if ($startC->lessThan($since)) { $startC = $since; }
                if ($endC->greaterThan($startC)) {
                    $seconds += $endC->diffInSeconds($startC);
                }
            }
        }
        // Convert to percentage and clamp between 0 and 100 to avoid negative/overflow values
        $utilization = ($seconds / (30 * 24 * 3600)) * 100;
        $utilization = max(0, min(100, round($utilization, 1)));

        return view('bo.trucks.show', [
            'truck' => $truckData,
            'statusCounts' => $statusCounts,
            'truckModel' => $truck,
            'utilization30' => $utilization,
        ]);
    }

    /**
     * Schedule a new deployment for the truck.
     */
    public function scheduleDeployment(ScheduleTruckDeploymentRequest $request, string $id)
    {
        $validated = $request->validated();

        // Check truck availability before scheduling
        $isAvailable = $this->checkTruckAvailability($id, $validated['deployment_date']);

        if (! $isAvailable) {
            return redirect()
                ->back()
                ->withErrors(['deployment_date' => 'Le camion n\'est pas disponible à cette date.']);
        }

        // Create deployment record with status tracking
        $deployment = $this->createDeployment($id, $validated);

        return redirect()
            ->route('bo.trucks.show', $id)
            ->with('success', "Déploiement #{$deployment['id']} programmé avec succès pour le ".$validated['deployment_date']);
    }

    /**
     * Open/start a scheduled deployment.
     */
    public function openDeployment(Request $request, string $id, string $deploymentId)
    {
        $request->validate([
            'start_time' => 'nullable|date_format:H:i',
            'location_confirmed' => 'required|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        // Update deployment status to 'active'
        $this->updateDeploymentStatus($deploymentId, 'active', [
            'start_time' => $request->input('start_time', now()->format('H:i')),
            'location_confirmed' => $request->boolean('location_confirmed'),
            'opening_notes' => $request->input('notes'),
        ]);

        return redirect()
            ->route('bo.trucks.show', $id)
            ->with('success', "Déploiement #{$deploymentId} ouvert et démarré.");
    }

    /**
     * Close/end an active deployment.
     */
    public function closeDeployment(Request $request, string $id, string $deploymentId)
    {
        $request->validate([
            'end_time' => 'nullable|date_format:H:i',
            'actual_revenue' => 'required|numeric|min:0',
            'issues_encountered' => 'nullable|string|max:1000',
            'customer_feedback' => 'nullable|string|max:500',
        ]);

        // Update deployment status to 'completed'
        $deployment = $this->updateDeploymentStatus($deploymentId, 'completed', [
            'end_time' => $request->input('end_time', now()->format('H:i')),
            'actual_revenue' => $request->input('actual_revenue'),
            'issues_encountered' => $request->input('issues_encountered'),
            'customer_feedback' => $request->input('customer_feedback'),
        ]);

        return redirect()
            ->route('bo.trucks.show', $id)
            ->with('success', "Déploiement #{$deploymentId} clôturé. Recettes: ".number_format($deployment['actual_revenue'] / 100, 2).'€');
    }

    /**
     * Schedule maintenance for the truck.
     */
    public function scheduleMaintenance(Request $request, string $id)
    {
        $request->validate([
            'date' => 'required|date|after:today',
            'type' => 'required|string|max:255',
            'technician' => 'required|string|max:255',
            'estimated_cost' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        // Create maintenance record and potentially update truck status
        $maintenance = $this->createMaintenanceRecord($id, $request->all());

        return redirect()
            ->route('bo.trucks.show', $id)
            ->with('success', "Maintenance #{$maintenance['id']} programmée avec succès pour le ".$request->input('date'));
    }

    /**
     * Open/start a scheduled maintenance.
     */
    public function openMaintenance(Request $request, string $id, string $maintenanceId)
    {
        $request->validate([
            'actual_start_time' => 'nullable|date_format:H:i',
            'technician_confirmed' => 'required|boolean',
            'initial_diagnosis' => 'nullable|string|max:1000',
        ]);

        // Update maintenance status to 'in_progress' and truck to 'maintenance'
        $this->updateMaintenanceStatus($maintenanceId, 'in_progress', [
            'actual_start_time' => $request->input('actual_start_time', now()->format('H:i')),
            'technician_confirmed' => $request->boolean('technician_confirmed'),
            'initial_diagnosis' => $request->input('initial_diagnosis'),
        ]);

        // Update truck status to maintenance
        $this->updateTruckStatus($id, 'InMaintenance', 'Maintenance en cours');

        return redirect()
            ->route('bo.trucks.show', $id)
            ->with('warning', "Maintenance #{$maintenanceId} démarrée. Le camion est maintenant indisponible.");
    }

    /**
     * Close/complete a maintenance session.
     */
    public function closeMaintenance(Request $request, string $id, string $maintenanceId)
    {
        $request->validate([
            'actual_end_time' => 'nullable|date_format:H:i',
            'actual_cost' => 'required|numeric|min:0',
            'work_performed' => 'required|string|max:1000',
            'parts_replaced' => 'nullable|string|max:500',
            'next_maintenance_date' => 'nullable|date|after:today',
            'truck_operational' => 'required|boolean',
        ]);

        // Update maintenance status to 'completed'
        $maintenance = $this->updateMaintenanceStatus($maintenanceId, 'completed', [
            'actual_end_time' => $request->input('actual_end_time', now()->format('H:i')),
            'actual_cost' => $request->input('actual_cost'),
            'work_performed' => $request->input('work_performed'),
            'parts_replaced' => $request->input('parts_replaced'),
            'next_maintenance_date' => $request->input('next_maintenance_date'),
        ]);

        // Update truck status based on operational state
        $newTruckStatus = $request->boolean('truck_operational') ? 'Active' : 'Retired';
        $this->updateTruckStatus($id, $newTruckStatus, 'Maintenance terminée');

        $statusMessage = $newTruckStatus === 'Active' ? 'Le camion est de nouveau opérationnel' : 'Le camion nécessite des réparations supplémentaires';

        return redirect()
            ->route('bo.trucks.show', $id)
            ->with('success', "Maintenance #{$maintenanceId} terminée. Coût: ".number_format($maintenance['actual_cost'] / 100, 2)."€. {$statusMessage}.");
    }

    /**
     * Update truck status (active, maintenance, inactive).
     */
    public function updateStatus(UpdateTruckStatusRequest $request, string $id)
    {
        $validated = $request->validated();
        $newStatus = $validated['status'];
        $reason = $validated['reason'] ?? null;

        $truck = Truck::findOrFail($id);
        $truck->update(['status' => $newStatus]);

        $statusLabels = [
            'active' => 'actif',
            'in_maintenance' => 'en maintenance',
            'retired' => 'retiré',
            'pending' => 'brouillon',
        ];

        return redirect()
            ->route('bo.trucks.show', $id)
            ->with('success', "Statut du camion mis à jour : {$statusLabels[$newStatus]}");
    }

    /**
     * Generate truck utilization report.
     */
    public function utilizationReport(Request $request)
    {
        $period = $request->input('period', 'custom');
        $from = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->subDays(30)->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfDay() : now();

        // Get all trucks with their deployments
        $trucks = Truck::with(['deployments'])->get();

        // Build printable rows expected by the Blade view
        $rows = $trucks->map(function (Truck $truck) use ($from, $to) {
            $seconds = 0;
            $activeDays = collect();

            foreach ($truck->deployments as $d) {
                $usingNew = Schema::hasTable('truck_deployments');
                $start = $usingNew ? ($d->actual_start_at ?? $d->planned_start_at) : $d->start_date;
                $end = $usingNew ? ($d->actual_end_at ?? $d->planned_end_at) : $d->end_date;
                if (!$start) { continue; }

                $startC = Carbon::parse($start);
                $endC = $end ? Carbon::parse($end) : $to;
                if ($endC->lt($from)) { continue; }
                if ($startC->lt($from)) { $startC = $from; }
                if ($endC->gt($to)) { $endC = $to; }
                if ($endC->gt($startC)) {
                    $seconds += $endC->diffInSeconds($startC);
                    // Mark each active day spanned by this deployment
                    $cursor = $startC->copy()->startOfDay();
                    while ($cursor->lte($endC)) {
                        $activeDays->push($cursor->toDateString());
                        $cursor->addDay();
                    }
                }
            }

            $hours = round($seconds / 3600, 1);
            $days = $activeDays->unique()->count();

            return [
                'truck' => $truck->plate,
                'km' => 0, // TODO: integrate odometer/sales data
                'hours' => $hours,
                'active_days' => $days,
                'revenue' => 0, // TODO: integrate revenue once available
            ];
        })->toArray();

        $fromStr = $from->format('Y-m-d');
        $toStr = $to->format('Y-m-d');

        return view('bo.trucks.utilization_report', [
            'rows' => $rows,
            'from' => $fromStr,
            'to' => $toStr,
        ]);
    }

    /**
     * Calculate truck utilization percentage.
     */
    private function calculateUtilization(Truck $truck): float
    {
        $totalDays = 30; // Last 30 days
        $deploymentDays = $truck->deployments()
            ->where('start_date', '>=', now()->subDays($totalDays))
            ->count();

        return $totalDays > 0 ? ($deploymentDays / $totalDays) * 100 : 0;
    }

    /**
     * Check if truck is available for deployment on given date.
     */
    private function checkTruckAvailability(string $id, string $date): bool
    {
        // Check for conflicting deployments
        $conflictingDeployments = Deployment::where('truck_id', $id)
            ->whereDate('start_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $date);
            })
            ->exists();

        // Check for maintenance on that date
        $maintenanceConflict = MaintenanceLog::where('truck_id', $id)
            ->whereDate(Schema::hasColumn('maintenance_logs', 'opened_at') ? 'opened_at' : 'started_at', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('closed_at')
                    ->orWhereDate('closed_at', '>=', $date);
            })
            ->exists();

        return ! $conflictingDeployments && ! $maintenanceConflict;
    }

    /**
     * Create a new deployment record.
     */
    private function createDeployment(string $truckId, array $data): array
    {
        $deployment = Deployment::create([
            'id' => Str::ulid()->toBase32(),
            'truck_id' => $truckId,
            'location' => $data['territory'],
            'start_date' => $data['deployment_date'],
        ]);

        return [
            'id' => $deployment->id,
            'truck_id' => $deployment->truck_id,
            'deployment_date' => $deployment->start_date->format('Y-m-d'),
            'territory' => $deployment->location,
            'franchisee_id' => $data['franchisee_id'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'scheduled',
        ];
    }

    /**
     * Update deployment status and associated data.
     */
    private function updateDeploymentStatus(string $deploymentId, string $status, array $data = []): array
    {
        $deployment = Deployment::findOrFail($deploymentId);

        if ($status === 'completed') {
            $deployment->update(['end_date' => now()]);
        }

        return array_merge([
            'id' => $deployment->id,
            'status' => $status,
            'updated_at' => now(),
        ], $data);
    }

    /**
     * Create a new maintenance record.
     */
    private function createMaintenanceRecord(string $truckId, array $data): array
    {
        $payload = [
            'id' => Str::ulid()->toBase32(),
            'truck_id' => $truckId,
            'description' => $data['description'] ?? null,
        ];
        if (Schema::hasColumn('maintenance_logs', 'type')) {
            $payload['type'] = $data['type'];
        } else {
            $payload['kind'] = ucfirst($data['type']);
        }
        if (Schema::hasColumn('maintenance_logs', 'opened_at')) {
            $payload['opened_at'] = $data['date'];
        } else {
            $payload['started_at'] = $data['date'];
        }

        $maintenance = MaintenanceLog::create($payload);

        return [
            'id' => $maintenance->id,
            'truck_id' => $maintenance->truck_id,
            'date' => ($maintenance->opened_at ?? $maintenance->started_at)?->format('Y-m-d'),
            'type' => ($maintenance->type ?? strtolower($maintenance->kind ?? '')),
            'technician' => $data['technician'],
            'estimated_cost' => $data['estimated_cost'] ?? 0,
            'description' => $maintenance->description,
            'status' => 'scheduled',
        ];
    }

    /**
     * Update maintenance status and associated data.
     */
    private function updateMaintenanceStatus(string $maintenanceId, string $status, array $data = []): array
    {
        $maintenance = MaintenanceLog::findOrFail($maintenanceId);

        if ($status === 'completed') {
            $maintenance->update(['closed_at' => now()]);
        }

        return array_merge([
            'id' => $maintenance->id,
            'status' => $status,
            'updated_at' => now(),
        ], $data);
    }

    /**
     * Update truck status with reason.
     */
    private function updateTruckStatus(string $truckId, string $status, ?string $reason = null): void
    {
        $truck = Truck::findOrFail($truckId);
        $truck->update(['status' => $status]);

        // TODO: Log status change for audit trail
    }

    /**
     * Download a private truck document (registration or insurance) for BO users.
     */
    public function downloadDocument(Request $request, string $truck, string $type)
    {
    $model = Truck::findOrFail($truck);
    $this->authorize('view', $model);

        $column = $type === 'registration' ? 'registration_doc_path' : 'insurance_doc_path';
        $path = $model->{$column};

        if (! $path || ! Storage::disk('local')->exists($path)) {
            return back()->with('error', __('ui.flash.file_not_found'));
        }

    return response()->download(Storage::disk('local')->path($path));
    }
}
