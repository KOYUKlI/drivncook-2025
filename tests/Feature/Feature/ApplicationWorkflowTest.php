<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create roles if they don't exist
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'franchisee']);

    // Create test users with unique emails
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->franchisee = User::factory()->create();
    $this->franchisee->assignRole('franchisee');
});

test('admin can access applications index', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.applications.index'));

    $response->assertStatus(200);
    $response->assertViewIs('bo.applications.index');
    $response->assertViewHas('applications');
});

test('non admin cannot access applications', function () {
    $response = $this->actingAs($this->franchisee)->get(route('bo.applications.index'));

    $response->assertStatus(403);
});

test('admin can view application details', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.applications.show', ['application' => 1]));

    $response->assertStatus(200);
    $response->assertViewIs('bo.applications.show');
    $response->assertViewHas('application');
});

test('admin can prequalify application', function () {
    Mail::fake();

    $response = $this->actingAs($this->admin)->post(route('bo.applications.prequalify', ['id' => 1]), [
        'message' => 'Votre dossier a été pré-qualifié',
    ]);

    $response->assertRedirect(route('bo.applications.show', 1));
    $response->assertSessionHas('success');

    Mail::assertSent(\App\Mail\ApplicationStatusChanged::class);
});

test('admin can schedule interview', function () {
    Mail::fake();

    $response = $this->actingAs($this->admin)->post(route('bo.applications.interview', ['id' => 1]), [
        'message' => 'Entretien programmé',
        'interview_date' => now()->addDays(7)->format('Y-m-d'),
    ]);

    $response->assertRedirect(route('bo.applications.show', 1));
    $response->assertSessionHas('success');

    Mail::assertSent(\App\Mail\ApplicationStatusChanged::class);
});

test('admin can approve application', function () {
    Mail::fake();

    $response = $this->actingAs($this->admin)->post(route('bo.applications.approve', ['id' => 1]), [
        'message' => 'Félicitations, candidature approuvée',
    ]);

    $response->assertRedirect(route('bo.applications.show', 1));
    $response->assertSessionHas('success');

    Mail::assertSent(\App\Mail\ApplicationStatusChanged::class);
});

test('admin can reject application', function () {
    Mail::fake();

    $response = $this->actingAs($this->admin)->post(route('bo.applications.reject', ['id' => 1]), [
        'reason' => 'Dossier incomplet',
    ]);

    $response->assertRedirect(route('bo.applications.index'));
    $response->assertSessionHas('success');

    Mail::assertSent(\App\Mail\ApplicationStatusChanged::class);
});

test('reject requires reason', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.applications.reject', ['id' => 1]), [
        // Missing required 'reason' field
    ]);

    $response->assertSessionHasErrors('reason');
});

test('interview date must be in future', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.applications.interview', ['id' => 1]), [
        'interview_date' => now()->subDay()->format('Y-m-d'), // Past date
    ]);

    $response->assertSessionHasErrors('interview_date');
});
