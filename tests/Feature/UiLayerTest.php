<?php

use App\Models\User;
use Database\Seeders\RolesAndUsersSeeder;

beforeEach(function () {
    $this->seed(RolesAndUsersSeeder::class);
    app()->setLocale('fr');
});

describe('Public Pages', function () {
    test('public home page is accessible', function () {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('public.home');
        $response->assertSee('Driv\'n Cook');
        $response->assertSee('Devenir franchisé');
    });

    test('franchise info page is accessible', function () {
        $response = $this->get('/devenir-franchise');

        $response->assertStatus(200);
        $response->assertViewIs('public.franchise-info');
        $response->assertSee('Opportunité de franchise');
        $response->assertSee('Postuler');
    });
});

describe('Dashboard Redirects', function () {
    test('admin user redirected to BO dashboard', function () {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/bo/dashboard');
    });

    test('franchisee user redirected to FO dashboard', function () {
        $user = User::factory()->create();
        $user->assignRole('franchisee');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/fo/dashboard');
    });

    test('unauthenticated user redirected to login', function () {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    });
});

describe('Back Office Access', function () {
    test('admin can access BO dashboard', function () {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/bo/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('bo.dashboard');
    });

    test('franchisee cannot access BO dashboard', function () {
        $user = User::factory()->create();
        $user->assignRole('franchisee');

        $response = $this->actingAs($user)->get('/bo/dashboard');

        $response->assertStatus(403);
    });

    test('admin can access franchisees index', function () {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/bo/franchisees');

        $response->assertStatus(200);
        $response->assertViewIs('bo.franchisees.index');
    });
});

describe('Front Office Access', function () {
    test('franchisee can access FO dashboard', function () {
        $user = User::factory()->create();
        $user->assignRole('franchisee');

        $response = $this->actingAs($user)->get('/fo/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('fo.dashboard');
    });

    test('franchisee can access sales index', function () {
        $user = User::factory()->create();
        $user->assignRole('franchisee');

        $response = $this->actingAs($user)->get('/fo/sales');

        $response->assertStatus(200);
        $response->assertViewIs('fo.sales.index');
    });

    test('admin cannot access FO routes', function () {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/fo/dashboard');

        $response->assertStatus(403);
    });
});

describe('Role-based Navigation', function () {
    test('sidebar shows correct links for admin', function () {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/bo/dashboard');

        $response->assertSee('Franchisés');
        $response->assertSee('Camions');
        $response->assertSee('Commandes d\'achat');
        $response->assertSee('Candidatures');
    });

    test('sidebar shows correct links for franchisee', function () {
        $user = User::factory()->create();
        $user->assignRole('franchisee');

        $response = $this->actingAs($user)->get('/fo/dashboard');

        $response->assertSee('Ventes');
        $response->assertSee('Rapports');
        $response->assertDontSee('Franchisés');
        $response->assertDontSee('Candidatures');
    });
});

describe('Layout Structure', function () {
    test('authenticated pages use app-shell layout', function () {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/bo/dashboard');

        $response->assertSee('min-h-screen flex flex-col', false);
        $response->assertViewHasAll(['user' => $user]);
    });

    test('public pages use guest layout', function () {
        $response = $this->get('/');

        $response->assertSee('Driv\'n Cook');
        $response->assertDontSee('Tableau de bord');
    });
});
