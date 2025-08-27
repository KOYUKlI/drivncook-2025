<?php

use App\Models\User;
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

test('warehouse can access purchase orders', function () {
    $response = $this->actingAs($this->warehouse)->get(route('bo.purchase-orders.index'));
    
    $response->assertStatus(200);
});

test('franchisee cannot access purchase orders', function () {
    $response = $this->actingAs($this->franchisee)->get(route('bo.purchase-orders.index'));
    
    $response->assertStatus(403);
});

test('can view purchase order with 80/20 ratio calculation', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.purchase-orders.show', ['purchase_order' => 1]));
    
    $response->assertStatus(200);
    $response->assertViewIs('bo.purchase_orders.show');
    $response->assertViewHas('order');
});

test('can approve non-compliant purchase order', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.validate-compliance', ['id' => 1]), [
        'action' => 'approve',
        'message' => 'Approuvé exceptionnellement'
    ]);
    
    $response->assertRedirect(route('bo.purchase-orders.show', 1));
    $response->assertSessionHas('success');
});

test('can flag non-compliant purchase order', function () {
    $response = $this->actingAs($this->warehouse)->post(route('bo.purchase-orders.validate-compliance', ['id' => 1]), [
        'action' => 'flag',
        'message' => 'Signalé pour révision'
    ]);
    
    $response->assertRedirect(route('bo.purchase-orders.show', 1));
    $response->assertSessionHas('warning');
});

test('can reject non-compliant purchase order', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.validate-compliance', ['id' => 1]), [
        'action' => 'reject',
        'message' => 'Ratio 80/20 non respecté'
    ]);
    
    $response->assertRedirect(route('bo.purchase-orders.index'));
    $response->assertSessionHas('success');
});

test('validate compliance requires valid action', function () {
    $response = $this->actingAs($this->admin)->post(route('bo.purchase-orders.validate-compliance', ['id' => 1]), [
        'action' => 'invalid_action'
    ]);
    
    $response->assertSessionHasErrors('action');
});

test('can access compliance report', function () {
    $response = $this->actingAs($this->admin)->get(route('bo.purchase-orders.compliance-report'));
    
    $response->assertStatus(200);
    $response->assertViewIs('bo.purchase_orders.compliance_report');
    $response->assertViewHas('complianceData');
});

test('compliance report with period filter', function () {
    $response = $this->actingAs($this->warehouse)->get(route('bo.purchase-orders.compliance-report', ['period' => 'last_month']));
    
    $response->assertStatus(200);
    $response->assertViewHas('complianceData');
    $response->assertViewHas('period');
});
