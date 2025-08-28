<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

describe('Complete Workflow Integration Tests', function () {
    beforeEach(function () {
        // Clear all caches to avoid conflicts between tests
        Artisan::call('view:clear');

        // Clear all roles and permissions to start fresh
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $franchiseeRole = Role::firstOrCreate(['name' => 'franchisee', 'guard_name' => 'web']);
        $warehouseRole = Role::firstOrCreate(['name' => 'warehouse', 'guard_name' => 'web']);
        $fleetRole = Role::firstOrCreate(['name' => 'fleet', 'guard_name' => 'web']);

        // Create users with roles
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);

        $this->franchiseeUser = User::factory()->create();
        $this->franchiseeUser->assignRole($franchiseeRole);

        $this->warehouseUser = User::factory()->create();
        $this->warehouseUser->assignRole($warehouseRole);

        $this->fleetUser = User::factory()->create();
        $this->fleetUser->assignRole($fleetRole);
    });
    it('allows complete franchise application workflow', function () {
        // 1. Public application submission
        $applicationData = [
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'jean.dupont@example.com',
            'phone' => '0123456789',
            'territory' => 'Paris Nord',
            'business_name' => 'Food Truck JD',
            'cv' => \Illuminate\Http\UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
            'identity' => \Illuminate\Http\UploadedFile::fake()->image('identity.jpg'),
        ];

        $response = $this->post(route('public.applications.store'), $applicationData);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 2. Admin can view and process applications
        $this->actingAs($this->adminUser);

        $response = $this->get(route('bo.applications.index'));
        $response->assertOk();

        // 3. Admin can approve application
        $response = $this->post(route('bo.applications.approve', '1'), [
            'message' => 'Application approuvée après review',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    });

    it('allows complete sales workflow for franchisees', function () {
        $this->actingAs($this->franchiseeUser);

        // 1. Franchisee can view sales dashboard
        $response = $this->get(route('fo.sales.index'));
        $response->assertOk();

        // 2. Franchisee can create new sale
        $response = $this->get(route('fo.sales.create'));
        $response->assertOk();

        // 3. Franchisee can store sale with validation
        $saleData = [
            'location' => 'Place de la République',
            'coordinates' => '48.8566, 2.3522',
            'payment_method' => 'card',
            'items' => [
                [
                    'product_id' => 1,
                    'quantity' => 2,
                    'unit_price' => 950,
                ],
                [
                    'product_id' => 4,
                    'quantity' => 1,
                    'unit_price' => 250,
                ],
            ],
        ];

        $response = $this->post(route('fo.sales.store'), $saleData);
        $response->assertRedirect(route('fo.sales.index'));
        $response->assertSessionHas('success');
    });

    it('allows truck management workflow', function () {
        $this->actingAs($this->fleetUser);

        // 1. Fleet manager can view trucks
        $response = $this->get(route('bo.trucks.index'));
        $response->assertOk();

        // 2. Fleet manager can view specific truck
        $response = $this->get(route('bo.trucks.show', '1'));
        $response->assertOk();

        // 3. Fleet manager can update truck status
        $response = $this->patch(route('bo.trucks.update-status', '1'), [
            'status' => 'maintenance',
            'reason' => 'Scheduled maintenance',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 4. Fleet manager can schedule deployment
        $response = $this->post(route('bo.trucks.schedule-deployment', '1'), [
            'franchisee_id' => '01HK1234567890123456789012',
            'territory' => 'Paris Centre',
            'deployment_date' => now()->addDays(1)->format('Y-m-d'),
            'notes' => 'Test deployment',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    });

    it('allows purchase order compliance workflow', function () {
        $this->actingAs($this->warehouseUser);

        // 1. Warehouse can view purchase orders
        $response = $this->get(route('bo.purchase-orders.index'));
        $response->assertOk();

        // 2. Warehouse can view specific purchase order
        $response = $this->get(route('bo.purchase-orders.show', '1'));
        $response->assertOk();

        // 3. Warehouse can validate compliance
        $response = $this->post(route('bo.purchase-orders.validate-compliance', '1'), [
            'compliance_status' => 'validated',
            'inspector_notes' => 'All items conform to standards',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    });

    it('enforces role-based access control', function () {
        // Franchisee cannot access BO routes
        $this->actingAs($this->franchiseeUser);

        $response = $this->get(route('bo.dashboard'));
        $response->assertForbidden();

        $response = $this->get(route('bo.applications.index'));
        $response->assertForbidden();

        // Admin cannot access FO routes directly
        $this->actingAs($this->adminUser);

        $response = $this->get(route('fo.dashboard'));
        $response->assertForbidden();
    });

    it('validates form requests properly', function () {
        $this->actingAs($this->franchiseeUser);

        // Test invalid sale data
        $invalidSaleData = [
            'location' => '', // Required field missing
            'payment_method' => 'invalid', // Invalid option
            'items' => [], // Empty array
        ];

        $response = $this->post(route('fo.sales.store'), $invalidSaleData);
        $response->assertSessionHasErrors(['location', 'payment_method', 'items']);
    });

    it('generates reports successfully', function () {
        $this->actingAs($this->franchiseeUser);

        // Generate sales report
        $reportData = [
            'report_type' => 'sales',
            'period_type' => 'monthly',
            'format' => 'pdf',
        ];

        $response = $this->post(route('fo.reports.generate'), $reportData);
        $response->assertRedirect(route('fo.reports.index'));
        $response->assertSessionHas('success');
    });
});
