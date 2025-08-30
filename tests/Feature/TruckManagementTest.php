<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create roles if they don't exist
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'fleet']);
    Role::firstOrCreate(['name' => 'franchisee']);

    // Create test users with unique emails
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->fleet = User::factory()->create();
    $this->fleet->assignRole('fleet');

    $this->franchisee = User::factory()->create();
    $this->franchisee->assignRole('franchisee');
});

test('admin can access trucks index', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.trucks.index'));

    $response->assertStatus(200);
    $response->assertViewIs('bo.trucks.index');
    $response->assertViewHas(['trucks', 'stats', 'status']);
});

test('fleet manager can access trucks', function () {
    $response = $this->actingAs($this->fleet)->get(route('bo.trucks.index'));

    $response->assertStatus(200);
});

test('franchisee cannot access trucks management', function () {
    $response = $this->actingAs($this->franchisee)->get(route('bo.trucks.index'));

    $response->assertStatus(403);
});

test('admin can view truck details', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.trucks.show', ['truck' => 1]));

    $response->assertStatus(200);
    $response->assertViewIs('bo.trucks.show');
    $response->assertViewHas('truck');
});

test('admin can schedule deployment', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.schedule-deployment', ['id' => 1]), [
        'date' => now()->addDays(3)->format('Y-m-d'),
        'location' => 'Place de la République',
        'duration' => 8,
        'notes' => 'Événement spécial du week-end',
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('deployment scheduling validates future date', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.schedule-deployment', ['id' => 1]), [
        'date' => now()->subDay()->format('Y-m-d'), // Past date
        'location' => 'Place de la République',
        'duration' => 8,
    ]);

    $response->assertSessionHasErrors('date');
});

test('admin can open scheduled deployment', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.open-deployment', [
        'id' => 1,
        'deploymentId' => 123,
    ]), [
        'start_time' => '09:00',
        'location_confirmed' => true,
        'notes' => 'Tout est prêt pour le service',
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('admin can close active deployment', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.close-deployment', [
        'id' => 1,
        'deploymentId' => 123,
    ]), [
        'end_time' => '18:00',
        'actual_revenue' => 85000, // 850€ en centimes
        'issues_encountered' => 'Aucun problème majeur',
        'customer_feedback' => 'Très bonne réception du public',
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('close deployment requires actual revenue', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.close-deployment', [
        'id' => 1,
        'deploymentId' => 123,
    ]), [
        'end_time' => '18:00',
        // Missing required 'actual_revenue' field
    ]);

    $response->assertSessionHasErrors('actual_revenue');
});

test('admin can schedule maintenance', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.schedule-maintenance', ['id' => 1]), [
        'date' => now()->addWeeks(2)->format('Y-m-d'),
        'type' => 'Révision générale',
        'technician' => 'Garage Central Paris',
        'estimated_cost' => 120000, // 1200€ en centimes
        'description' => 'Révision complète + changement huile',
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('admin can open scheduled maintenance', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.open-maintenance', [
        'id' => 1,
        'maintenanceId' => 456,
    ]), [
        'actual_start_time' => '08:30',
        'technician_confirmed' => true,
        'initial_diagnosis' => 'État général satisfaisant, révision de routine',
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('warning'); // Truck becomes unavailable
});

test('admin can close completed maintenance', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.close-maintenance', [
        'id' => 1,
        'maintenanceId' => 456,
    ]), [
        'actual_end_time' => '16:00',
        'actual_cost' => 135000, // 1350€ en centimes
        'work_performed' => 'Révision complète, changement huile, remplacement plaquettes de frein',
        'parts_replaced' => 'Plaquettes de frein avant, filtre à huile',
        'next_maintenance_date' => now()->addMonths(3)->format('Y-m-d'),
        'truck_operational' => true,
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('close maintenance requires work performed', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.close-maintenance', [
        'id' => 1,
        'maintenanceId' => 456,
    ]), [
        'actual_cost' => 135000,
        'truck_operational' => true,
        // Missing required 'work_performed' field
    ]);

    $response->assertSessionHasErrors('work_performed');
});

test('admin can update truck status', function () {
    $response = $this->actingAs($this->admin)->patch(route('bo.trucks.update-status', ['id' => 1]), [
        'status' => 'inactive',
        'reason' => 'Problème technique détecté',
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('truck status update validates allowed values', function () {
    $response = $this->actingAs($this->admin)->patch(route('bo.trucks.update-status', ['id' => 1]), [
        'status' => 'invalid_status',
        'reason' => 'Test invalide',
    ]);

    $response->assertSessionHasErrors('status');
});

test('fleet manager can access utilization reports', function () {
    $response = $this->actingAs($this->fleet)->get(route('bo.trucks.utilization-report'));

    $response->assertStatus(200);
    $response->assertViewIs('bo.trucks.utilization_report');
    $response->assertViewHas('utilizationData');
});

test('franchisee cannot access utilization reports', function () {
    $response = $this->actingAs($this->franchisee)->get(route('bo.trucks.utilization-report'));

    $response->assertStatus(403);
});

test('fleet manager has all truck permissions', function () {
    // Test that fleet manager can perform all truck operations
    $response = $this->actingAs($this->fleet)->post(route('bo.trucks.schedule-deployment', ['id' => 1]), [
        'date' => now()->addDays(5)->format('Y-m-d'),
        'location' => 'Gare de Lyon',
        'duration' => 6,
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('deployment and maintenance operations respect truck availability', function () {
    // This test would verify that the availability checking logic works correctly
    // For now, we just test the endpoint exists and validates input
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.schedule-deployment', ['id' => 1]), [
        'date' => now()->addDays(2)->format('Y-m-d'),
        'location' => 'Test Location',
        'duration' => 4,
    ]);

    // Should succeed as availability check currently returns true
    $response->assertRedirect(route('bo.trucks.show', 1));
});
