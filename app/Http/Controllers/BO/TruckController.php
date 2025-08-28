<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleTruckDeploymentRequest;
use App\Http\Requests\UpdateTruckStatusRequest;
use App\Models\Truck;
use App\Models\Deployment;
use App\Models\MaintenanceLog;
use App\Models\Franchisee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TruckController extends Controller
{
    /**
     * Display a listing of trucks with status filtering.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = Truck::query()->with('franchisee');

        // Filter trucks based on status - map frontend values to enum values
        if ($status !== 'all') {
            $statusMap = [
                'active' => 'Active',
                'maintenance' => 'InMaintenance',
                'inactive' => 'Retired'
            ];
            
            if (isset($statusMap[$status])) {
                $query->where('status', $statusMap[$status]);
            }
        }

        $trucks = $query->get()->map(function ($truck) {
            // Map status enum values to frontend expected values
            $statusMap = [
                'Active' => 'active',
                'InMaintenance' => 'maintenance', 
                'Retired' => 'inactive',
                'Draft' => 'inactive'
            ];
            
            return [
                'id' => $truck->id,
                'code' => $truck->plate, // use plate as code
                'status' => $statusMap[$truck->status] ?? 'inactive',
                'franchisee' => $truck->franchisee->name ?? 'Non assigné',
                'last_maintenance' => $truck->maintenanceLogs()->latest('started_at')->first()?->started_at?->format('Y-m-d'),
                'next_maintenance' => $truck->maintenanceLogs()
                    ->where('started_at', '>', now())
                    ->orderBy('started_at')
                    ->first()?->started_at?->format('Y-m-d'),
            ];
        })->toArray();

        // Calculate statistics
        $allTrucks = Truck::all();
        $stats = [
            'total' => $allTrucks->count(),
            'active' => $allTrucks->where('status', 'Active')->count(),
            'maintenance' => $allTrucks->where('status', 'InMaintenance')->count(),
            'inactive' => $allTrucks->where('status', 'Retired')->count(),
        ];

        return view('bo.trucks.index', compact('trucks', 'stats', 'status'));
    }

    /**
     * Display the specified truck with tabs (deployments, maintenance).
     */
    public function show(string $id)
    {
        $truck = Truck::with(['franchisee', 'deployments', 'maintenanceLogs'])->findOrFail($id);

        // Transform truck data to expected format
        $statusMap = [
            'Active' => 'active',
            'InMaintenance' => 'maintenance', 
            'Retired' => 'inactive',
            'Draft' => 'inactive'
        ];
        
        $truckData = [
            'id' => $truck->id,
            'code' => $truck->plate,
            'status' => $statusMap[$truck->status] ?? 'inactive',
            'franchisee' => $truck->franchisee->name ?? 'Non assigné',
            'franchisee_email' => $truck->franchisee->email ?? 'Non renseigné',
            'model' => 'N/A', // TODO: Add model field
            'license_plate' => $truck->plate,
            'purchase_date' => $truck->service_start?->format('Y-m-d'),
            'warranty_end' => 'N/A', // TODO: Add warranty field
            'deployments' => $truck->deployments->map(function ($deployment) {
                return [
                    'id' => $deployment->id,
                    'date' => $deployment->start_date?->format('Y-m-d'),
                    'location' => $deployment->location,
                    'revenue' => 0, // TODO: Calculate from sales
                    'status' => $deployment->end_date ? 'completed' : 'scheduled',
                ];
            })->toArray(),
            'maintenance' => $truck->maintenanceLogs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'date' => $log->started_at?->format('Y-m-d'),
                    'type' => $log->kind,
                    'cost' => 0, // TODO: Add cost field to maintenance logs
                    'status' => $log->closed_at ? 'completed' : ($log->started_at <= now() ? 'in_progress' : 'scheduled'),
                    'technician' => 'N/A', // TODO: Add technician field
                ];
            })->toArray(),
        ];

        // Calculate status counts for trucks
        $statusCounts = [
            'active' => Truck::where('status', 'Active')->count(),
            'in_maintenance' => Truck::where('status', 'InMaintenance')->count(),
            'retired' => Truck::where('status', 'Retired')->count(),
            'pending' => Truck::where('status', 'Draft')->count(),
        ];

        return view('bo.trucks.show', ['truck' => $truckData, 'statusCounts' => $statusCounts]);
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
            'available' => 'disponible',
            'deployed' => 'déployé',
            'maintenance' => 'en maintenance',
            'out_of_service' => 'hors service',
            'active' => 'actif',
            'inactive' => 'inactif',
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
        $period = $request->input('period', 'current_month');

        // Get all trucks with their deployments and maintenance logs
        $trucks = Truck::with(['deployments', 'maintenanceLogs', 'sales'])->get();
        
        $utilizationData = [
            'period' => $period,
            'total_trucks' => $trucks->count(),
            'total_deployments' => Deployment::count(),
            'total_revenue' => 0, // TODO: Calculate from sales
            'by_truck' => $trucks->map(function ($truck) {
                return [
                    'code' => $truck->plate,
                    'deployments' => $truck->deployments->count(),
                    'utilization' => $this->calculateUtilization($truck),
                    'revenue' => 0, // TODO: Calculate from sales
                ];
            })->toArray(),
            'maintenance_impact' => [
                'days_in_maintenance' => MaintenanceLog::whereNull('closed_at')->count(),
                'revenue_lost' => 0, // TODO: Calculate lost revenue
                'avg_maintenance_duration' => 0, // TODO: Calculate average duration
            ],
        ];

        $utilizationData['average_utilization'] = $trucks->count() > 0 
            ? collect($utilizationData['by_truck'])->avg('utilization')
            : 0;

        return view('bo.trucks.utilization_report', compact('utilizationData'));
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
            ->whereDate('started_at', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('closed_at')
                    ->orWhereDate('closed_at', '>=', $date);
            })
            ->exists();

        return !$conflictingDeployments && !$maintenanceConflict;
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
        $maintenance = MaintenanceLog::create([
            'id' => Str::ulid()->toBase32(),
            'truck_id' => $truckId,
            'kind' => $data['type'],
            'description' => $data['description'] ?? null,
            'started_at' => $data['date'],
        ]);

        return [
            'id' => $maintenance->id,
            'truck_id' => $maintenance->truck_id,
            'date' => $maintenance->started_at->format('Y-m-d'),
            'type' => $maintenance->kind,
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
}
