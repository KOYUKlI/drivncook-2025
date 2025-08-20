<?php

namespace Tests\Feature;

use App\Mail\FranchiseApplicationApproved;
use App\Mail\FranchiseApplicationReceived;
use App\Mail\FranchiseApplicationRejected;
use App\Models\Franchise;
use App\Models\FranchiseApplication;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class FranchiseApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_application_creates_pending_and_sends_emails(): void
    {
        Mail::fake();

        $resp = $this->post(route('franchise.apply.post'), [
            'full_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0600000000',
            'city' => 'Lyon',
            'budget' => 60000,
            'experience' => 'Restauration',
            'motivation' => 'Très motivée par le concept et le format.',
            'gdpr' => '1',
        ]);

        $resp->assertRedirect(route('franchise.apply'));
        $this->assertDatabaseHas('franchise_applications', [
            'email' => 'jane@example.com',
            'status' => 'pending',
        ]);

        $app = FranchiseApplication::firstWhere('email', 'jane@example.com');
        Mail::assertQueued(FranchiseApplicationReceived::class, function ($m) use ($app) {
            return $m->application->is($app);
        });
    }

    public function test_admin_can_approve_creates_franchise_and_user_and_sends_reset(): void
    {
        Notification::fake();
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $app = FranchiseApplication::factory()->create([
            'full_name' => 'Paul Manager',
            'email' => 'paul@example.com',
            'status' => 'pending',
        ]);

        $resp = $this->actingAs($admin)
            ->post(route('admin.franchise-applications.approve', $app->id), [
                'franchise_name' => 'Driv\'n Cook Lyon',
            ]);

        $resp->assertRedirect();
        $resp->assertSessionHas('success');

    $this->assertDatabaseHas('franchises', ['name' => "Driv'n Cook Lyon"]);
        $user = User::firstWhere('email', 'paul@example.com');
        $this->assertNotNull($user);
        $this->assertSame('franchise', $user->role);
        $this->assertNotNull($user->franchise_id);
        Notification::assertSentTo($user, ResetPassword::class);
        Mail::assertQueued(FranchiseApplicationApproved::class);

        $app->refresh();
        $this->assertSame('accepted', $app->status);
        $this->assertNotNull($app->reviewed_at);
    }

    public function test_admin_can_reject_marks_rejected_and_sends_mail(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $app = FranchiseApplication::factory()->create([
            'full_name' => 'Nina Ops',
            'email' => 'nina@example.com',
            'status' => 'pending',
        ]);

        $resp = $this->actingAs($admin)
            ->post(route('admin.franchise-applications.reject', $app->id));

        $resp->assertRedirect();
        $resp->assertSessionHas('success');
        $app->refresh();
        $this->assertSame('rejected', $app->status);
        $this->assertNotNull($app->reviewed_at);
        Mail::assertQueued(FranchiseApplicationRejected::class);
    }

    public function test_approval_blocks_if_existing_user_attached_elsewhere(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherFranchise = Franchise::factory()->create(['name' => 'Other F']);
        $existing = User::factory()->create([
            'email' => 'alex@example.com',
            'role' => 'franchise',
            'franchise_id' => $otherFranchise->id,
        ]);
        $app = FranchiseApplication::factory()->create([
            'full_name' => 'Alex Existing',
            'email' => 'alex@example.com',
            'status' => 'pending',
        ]);

        $resp = $this->actingAs($admin)
            ->post(route('admin.franchise-applications.approve', $app->id), [
                'franchise_name' => 'Driv\'n Cook Test',
            ]);

        $this->assertTrue(in_array($resp->getStatusCode(), [302, 422]));
        $this->assertDatabaseHas('franchise_applications', ['id' => $app->id, 'status' => 'pending']);
    }

    public function test_public_register_forces_customer_role(): void
    {
        // Try to tamper role as 'admin' but controller must force 'customer'
        $resp = $this->post(route('register'), [
            'name' => 'Tamper User',
            'email' => 'tamper@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);
        $resp->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'tamper@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_admin_create_user_under_franchise_sends_reset_link(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $franchise = \App\Models\Franchise::factory()->create(['name' => 'Test F']);

        $resp = $this->actingAs($admin)
            ->post(route('admin.franchises.users.store', $franchise), [
                'name' => 'New Member',
                'email' => 'member@example.com',
                'role' => 'franchise',
            ]);

        $resp->assertRedirect(route('admin.franchisees.show', $franchise));
        $user = User::firstWhere('email', 'member@example.com');
        $this->assertNotNull($user);
        $this->assertSame('franchise', $user->role);
        $this->assertSame($franchise->id, $user->franchise_id);
        Notification::assertSentTo($user, \Illuminate\Auth\Notifications\ResetPassword::class);
    }

    public function test_attach_by_email_blocks_when_user_already_attached_without_transfer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $f1 = \App\Models\Franchise::factory()->create(['name' => 'A']);
        $f2 = \App\Models\Franchise::factory()->create(['name' => 'B']);
        $u = User::factory()->create(['email' => 'x@example.com', 'role' => 'franchise', 'franchise_id' => $f1->id]);

        $resp = $this->actingAs($admin)
            ->post(route('admin.franchisees.users.attach', $f2), [
                'email' => 'x@example.com',
                // no transfer flag
            ]);

        $resp->assertSessionHas('error');
        $u->refresh();
        $this->assertSame($f1->id, $u->franchise_id);
    }
}
