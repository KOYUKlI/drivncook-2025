<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleTruckDeploymentRequest;
use App\Http\Requests\UpdateTruckStatusRequest;
use Illuminate\Http\Request;

class TruckController extends Controller
{
    /**
     * Display a listing of trucks with status filtering.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');

        // Mock data - in real app, would filter from database
        $allTrucks = [
            ['id' => 1, 'code' => 'C001', 'status' => 'active', 'franchisee' => 'Paris Nord', 'last_maintenance' => '2024-08-15', 'next_maintenance' => '2024-11-15'],
            ['id' => 2, 'code' => 'C002', 'status' => 'maintenance', 'franchisee' => 'Lyon Centre', 'last_maintenance' => '2024-08-20', 'next_maintenance' => '2024-08-30'],
            ['id' => 3, 'code' => 'C003', 'status' => 'active', 'franchisee' => 'Marseille Sud', 'last_maintenance' => '2024-08-10', 'next_maintenance' => '2024-11-10'],
            ['id' => 4, 'code' => 'C004', 'status' => 'inactive', 'franchisee' => 'Toulouse Nord', 'last_maintenance' => '2024-07-20', 'next_maintenance' => '2024-10-20'],
            ['id' => 5, 'code' => 'C005', 'status' => 'active', 'franchisee' => 'Bordeaux Est', 'last_maintenance' => '2024-08-22', 'next_maintenance' => '2024-11-22'],
        ];

        // Filter trucks based on status
        $trucks = $status === 'all' ? $allTrucks :
            array_filter($allTrucks, fn ($truck) => $truck['status'] === $status);

        // Calculate statistics
        $stats = [
            'total' => count($allTrucks),
            'active' => count(array_filter($allTrucks, fn ($t) => $t['status'] === 'active')),
            'maintenance' => count(array_filter($allTrucks, fn ($t) => $t['status'] === 'maintenance')),
            'inactive' => count(array_filter($allTrucks, fn ($t) => $t['status'] === 'inactive')),
        ];

        return view('bo.trucks.index', compact('trucks', 'stats', 'status'));
    }

    /**
     * Display the specified truck with tabs (deployments, maintenance).
     */
    public function show(string $id)
    {
        // Mock data
        $truck = [
            'id' => $id,
            'code' => 'C001',
            'status' => 'active',
            'franchisee' => 'Paris Nord',
            'franchisee_email' => 'franchise.parisnord@drivncook.fr',
            'model' => 'Food Truck Pro 2023',
            'license_plate' => 'AB-123-CD',
            'purchase_date' => '2023-03-15',
            'warranty_end' => '2025-03-15',
            'deployments' => [
                ['id' => 1, 'date' => '2024-08-27', 'location' => 'Place de la République', 'revenue' => 850, 'status' => 'completed'],
                ['id' => 2, 'date' => '2024-08-26', 'location' => 'Gare du Nord', 'revenue' => 920, 'status' => 'completed'],
                ['id' => 3, 'date' => '2024-08-28', 'location' => 'Châtelet-Les Halles', 'revenue' => 0, 'status' => 'scheduled'],
            ],
            'maintenance' => [
                ['id' => 1, 'date' => '2024-08-15', 'type' => 'Révision générale', 'cost' => 1200, 'status' => 'completed', 'technician' => 'Garage Central'],
                ['id' => 2, 'date' => '2024-08-25', 'type' => 'Changement pneus', 'cost' => 450, 'status' => 'scheduled', 'technician' => 'Pneus Service'],
                ['id' => 3, 'date' => '2024-09-10', 'type' => 'Contrôle technique', 'cost' => 85, 'status' => 'pending', 'technician' => 'CT Auto'],
            ],
        ];

        // Provide safe stats with required keys
        $statusCounts = [
            'active' => 3,
            'in_maintenance' => 1,
            'retired' => 1,
            'pending' => 0,
        ];

        return view('bo.trucks.show', compact('truck', 'statusCounts'));
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
        $this->updateTruckStatus($id, 'maintenance', 'Maintenance en cours');

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
        $newTruckStatus = $request->boolean('truck_operational') ? 'active' : 'inactive';
        $this->updateTruckStatus($id, $newTruckStatus, 'Maintenance terminée');

        $statusMessage = $newTruckStatus === 'active' ? 'Le camion est de nouveau opérationnel' : 'Le camion nécessite des réparations supplémentaires';

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

        // In real app: Update database, create status log, notify franchisee if needed

        $statusLabels = [
            'available' => 'disponible',
            'deployed' => 'déployé',
            'maintenance' => 'en maintenance',
            'out_of_service' => 'hors service',
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

        // Mock utilization data
        $utilizationData = [
            'period' => $period,
            'total_trucks' => 5,
            'average_utilization' => 78.5,
            'total_deployments' => 156,
            'total_revenue' => 142350, // centimes
            'by_truck' => [
                ['code' => 'C001', 'deployments' => 22, 'utilization' => 85.2, 'revenue' => 18750],
                ['code' => 'C002', 'deployments' => 18, 'utilization' => 72.1, 'revenue' => 15200],
                ['code' => 'C003', 'deployments' => 25, 'utilization' => 89.3, 'revenue' => 21400],
                ['code' => 'C004', 'deployments' => 15, 'utilization' => 65.8, 'revenue' => 12850],
                ['code' => 'C005', 'deployments' => 20, 'utilization' => 78.9, 'revenue' => 17100],
            ],
            'maintenance_impact' => [
                'days_in_maintenance' => 12,
                'revenue_lost' => 8500,
                'avg_maintenance_duration' => 2.4,
            ],
        ];

        return view('bo.trucks.utilization_report', compact('utilizationData'));
    }

    /**
     * Check if truck is available for deployment on given date.
     */
    private function checkTruckAvailability(string $id, string $date): bool
    {
        // Mock availability check - in real app, would query database
        // Check for conflicting deployments or maintenance
        return true; // Simplified for demo
    }

    /**
     * Create a new deployment record.
     */
    private function createDeployment(string $truckId, array $data): array
    {
        // Mock deployment creation - in real app, would save to database
        return [
            'id' => rand(1000, 9999),
            'truck_id' => $truckId,
            'deployment_date' => $data['deployment_date'],
            'territory' => $data['territory'],
            'franchisee_id' => $data['franchisee_id'],
            'notes' => $data['notes'] ?? null,
            'status' => 'scheduled',
        ];
    }

    /**
     * Update deployment status and associated data.
     */
    private function updateDeploymentStatus(string $deploymentId, string $status, array $data = []): array
    {
        // Mock status update - in real app, would update database
        return array_merge([
            'id' => $deploymentId,
            'status' => $status,
            'updated_at' => now(),
        ], $data);
    }

    /**
     * Create a new maintenance record.
     */
    private function createMaintenanceRecord(string $truckId, array $data): array
    {
        // Mock maintenance creation - in real app, would save to database
        return [
            'id' => rand(1000, 9999),
            'truck_id' => $truckId,
            'date' => $data['date'],
            'type' => $data['type'],
            'technician' => $data['technician'],
            'estimated_cost' => $data['estimated_cost'] ?? 0,
            'description' => $data['description'] ?? null,
            'status' => 'scheduled',
        ];
    }

    /**
     * Update maintenance status and associated data.
     */
    private function updateMaintenanceStatus(string $maintenanceId, string $status, array $data = []): array
    {
        // Mock status update - in real app, would update database
        return array_merge([
            'id' => $maintenanceId,
            'status' => $status,
            'updated_at' => now(),
        ], $data);
    }

    /**
     * Update truck status with reason.
     */
    private function updateTruckStatus(string $truckId, string $status, ?string $reason = null): void
    {
        // Mock truck status update - in real app, would update database
        // Also log status change for audit trail
    }
}
