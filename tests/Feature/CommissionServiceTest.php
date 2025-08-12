<?php

use App\Models\{CustomerOrder, Truck, Franchise, Commission, User};
use App\Services\CommissionService;
use Illuminate\Support\Facades\App;

it('computes monthly commissions at 4 percent', function () {
    $franchise = Franchise::factory()->create();
    $truck = Truck::factory()->create(['franchise_id' => $franchise->id]);
    User::factory()->create(['role' => 'franchise','franchise_id' => $franchise->id]);
    $month = now()->format('Y-m');
    CustomerOrder::factory()->create([
        'truck_id' => $truck->id,
        'total_price' => 100,
        'status' => 'completed',
        'payment_status' => 'paid',
        'ordered_at' => now()->startOfMonth()->addDay(),
    ]);
    CustomerOrder::factory()->create([
        'truck_id' => $truck->id,
        'total_price' => 50,
        'status' => 'completed',
        'payment_status' => 'paid',
        'ordered_at' => now()->startOfMonth()->addDays(2),
    ]);
    CustomerOrder::factory()->create([
        'truck_id' => $truck->id,
        'total_price' => 999,
        'status' => 'completed',
        'payment_status' => 'paid',
        'ordered_at' => now()->copy()->subMonth(),
    ]);

    /** @var CommissionService $svc */
    $svc = App::make(CommissionService::class);
    $svc->computeMonth($month);

    $c = Commission::first();
    expect($c)->not()->toBeNull();
    expect((float)$c->turnover)->toBe(150.0);
    expect((float)$c->rate)->toBe(4.0);
    expect((float)$c->amount_due)->toBe(6.0);
});
