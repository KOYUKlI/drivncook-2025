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
    $response->assertViewHas(['trucks', 'stats']);
});

test('fleet manager can access trucks', function () {
    $response = $this->actingAs($this->fleet)->get(route('bo.trucks.index'));

    $response->assertStatus(200);
});

test('franchisee cannot access trucks management', function () {
    $response = $this->actingAs($this->franchisee)->get(route('bo.trucks.index'));

    $response->assertStatus(403);
});

test('can filter trucks by status', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.trucks.index', ['status' => 'active']));

    $response->assertStatus(200);
    $response->assertViewHas('status');
});

test('can view truck details', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.trucks.show', ['truck' => 1]));

    $response->assertStatus(200);
    $response->assertViewIs('bo.trucks.show');
    $response->assertViewHas('truck');
});

test('can schedule truck deployment', function () {
    $response = $this->actingAs($this->fleet)->post(route('bo.trucks.schedule-deployment', ['id' => 1]), [
        'date' => now()->addDays(3)->format('Y-m-d'),
        'location' => 'Place de la République',
        'duration' => 8,
        'notes' => 'Événement spécial',
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('deployment date must be in future', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.trucks.schedule-deployment', ['id' => 1]), [
        'date' => now()->subDay()->format('Y-m-d'),
        'location' => 'Test Location',
        'duration' => 8,
    ]);

    $response->assertSessionHasErrors('date');
});

test('can schedule truck maintenance', function () {
    $response = $this->actingAs($this->fleet)->post(route('bo.trucks.schedule-maintenance', ['id' => 1]), [
        'date' => now()->addWeeks(2)->format('Y-m-d'),
        'type' => 'Révision générale',
        'technician' => 'Garage Central',
        'estimated_cost' => 1500.00,
        'description' => 'Maintenance préventive',
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('can update truck status', function () {
    $response = $this->actingAs($this->admin)->patch(route('bo.trucks.update-status', ['id' => 1]), [
        'status' => 'maintenance',
        'reason' => 'Panne moteur',
    ]);

    $response->assertRedirect(route('bo.trucks.show', 1));
    $response->assertSessionHas('success');
});

test('truck status must be valid', function () {
    $response = $this->actingAs($this->admin)->patch(route('bo.trucks.update-status', ['id' => 1]), [
        'status' => 'invalid_status',
    ]);

    $response->assertSessionHasErrors('status');
});

test('can access utilization report', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.trucks.utilization-report'));

    $response->assertStatus(200);
    $response->assertViewIs('bo.trucks.utilization_report');
    $response->assertViewHas('utilizationData');
});

test('utilization report with period filter', function () {
    $response = $this->actingAs($this->fleet)->get(route('bo.trucks.utilization-report', ['period' => 'last_quarter']));

    $response->assertStatus(200);
    $response->assertViewHas('utilizationData');
});
