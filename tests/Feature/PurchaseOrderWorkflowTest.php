<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create roles if they don't exist
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'warehouse']);
    Role::firstOrCreate(['name' => 'franchisee']);

    // Create test users with unique emails
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->warehouse = User::factory()->create();
    $this->warehouse->assignRole('warehouse');

    $this->franchisee = User::factory()->create();
    $this->franchisee->assignRole('franchisee');
});

test('admin can access purchase orders index', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.purchase-orders.index'));

    $response->assertStatus(200);
    $response->assertViewIs('bo.purchase_orders.index');
    $response->assertViewHas('orders');
});

test('warehouse staff can access purchase orders', function () {
    $response = $this->actingAs($this->warehouse)->get(route('bo.purchase-orders.index'));

    $response->assertStatus(200);
});

test('franchisee cannot access purchase orders', function () {
    $response = $this->actingAs($this->franchisee)->get(route('bo.purchase-orders.index'));

    $response->assertStatus(403);
});

test('admin can view purchase order details', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.purchase-orders.show', ['purchase_order' => 1]));

    $response->assertStatus(200);
    $response->assertViewIs('bo.purchase_orders.show');
    $response->assertViewHas('order');
});

test('admin can validate compliance and approve', function () {
    Log::spy();

    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.validate-compliance', ['id' => 1]), [
        'action' => 'approve',
        'override_reason' => 'Situation exceptionnelle validée par la direction',
    ]);

    $response->assertRedirect(route('bo.purchase-orders.show', 1));
    $response->assertSessionHas('success');

    Log::assertLogged('info', function ($message, $context) {
        return str_contains($message, 'compliance override') &&
               isset($context['reason']) &&
               str_contains($context['reason'], 'Situation exceptionnelle');
    });
});

test('admin can flag purchase order for review', function () {
    Log::spy();

    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.validate-compliance', ['id' => 1]), [
        'action' => 'flag',
        'message' => 'Ratio inhabituel, à vérifier avec le franchisé',
    ]);

    $response->assertRedirect(route('bo.purchase-orders.show', 1));
    $response->assertSessionHas('warning');

    Log::assertLogged('warning', function ($message, $context) {
        return str_contains($message, 'flagged for review');
    });
});

test('admin can reject purchase order', function () {
    Log::spy();

    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.validate-compliance', ['id' => 1]), [
        'action' => 'reject',
        'message' => 'Ratio 80/20 non respecté, modification requise',
    ]);

    $response->assertRedirect(route('bo.purchase-orders.index'));
    $response->assertSessionHas('success');

    Log::assertLogged('info', function ($message, $context) {
        return str_contains($message, 'rejected');
    });
});

test('admin can update central ratio', function () {
    Log::spy();

    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.update-ratio', ['id' => 1]), [
        'central_ratio' => 85.5,
        'reason' => 'Ajustement basé sur les stocks actuels',
    ]);

    $response->assertRedirect(route('bo.purchase-orders.show', 1));
    $response->assertSessionHas('success');

    Log::assertLogged('info', function ($message, $context) {
        return str_contains($message, 'central ratio updated') &&
               $context['new_ratio'] === 85.5;
    });
});

test('warehouse staff cannot update central ratio', function () {
    $response = $this->actingAs($this->warehouse)->post(route('bo.purchase-orders.update-ratio', ['id' => 1]), [
        'central_ratio' => 85.5,
        'reason' => 'Tentative non autorisée',
    ]);

    $response->assertStatus(403);
});

test('admin can recalculate central ratio', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.recalculate', ['id' => 1]), [
        'force_recalculation' => true,
    ]);

    $response->assertRedirect(route('bo.purchase-orders.show', 1));
    $response->assertSessionHas('success');
});

test('approve action requires override reason', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.validate-compliance', ['id' => 1]), [
        'action' => 'approve',
        // Missing required 'override_reason' field
    ]);

    $response->assertSessionHasErrors('override_reason');
});

test('update ratio requires valid percentage', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.update-ratio', ['id' => 1]), [
        'central_ratio' => 150, // Invalid: > 100%
        'reason' => 'Test invalide',
    ]);

    $response->assertSessionHasErrors('central_ratio');
});

test('update ratio requires reason', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.update-ratio', ['id' => 1]), [
        'central_ratio' => 85,
        // Missing required 'reason' field
    ]);

    $response->assertSessionHasErrors('reason');
});

test('central ratio computation respects 80/20 rule', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.purchase-orders.show', ['purchase_order' => 1]));

    $response->assertStatus(200);
    $response->assertViewHas('order', function ($order) {
        // Central ratio should be computed and comply with 80% minimum
        return isset($order['calculated_ratio']) &&
               $order['calculated_ratio'] >= 80;
    });
});

test('compliance report accessible to admin and warehouse', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.purchase-orders.compliance-report'));

    $response->assertStatus(200);
    $response->assertViewIs('bo.purchase_orders.compliance_report');
    $response->assertViewHas('complianceData');

    $response = $this->actingAs($this->warehouse)->get(route('bo.purchase-orders.compliance-report'));

    $response->assertStatus(200);
});

test('compliance report not accessible to franchisee', function () {
    $response = $this->actingAs($this->franchisee)->get(route('bo.purchase-orders.compliance-report'));

    $response->assertStatus(403);
});
