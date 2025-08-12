<?php

use App\Models\User;
use App\Models\LoyaltyRule;
use function Pest\Laravel\{actingAs, post, put, get};

it('creates a loyalty rule and enforces single active rule', function() {
    $admin = User::factory()->create(['role'=>'admin']);
    actingAs($admin);
    post(route('admin.loyalty-rules.store'), [
        'points_per_euro' => 1,
        'redeem_rate' => 0.1,
        'expires_after_months' => 12,
        'active' => 1,
        '_token'=>csrf_token()
    ])->assertRedirect(route('admin.loyalty-rules.index'));
    $r1 = LoyaltyRule::first();
    expect($r1->active)->toBeTrue();
    post(route('admin.loyalty-rules.store'), [
        'points_per_euro' => 2,
        'redeem_rate' => 0.2,
        'expires_after_months' => 6,
        'active' => 1,
        '_token'=>csrf_token()
    ])->assertRedirect(route('admin.loyalty-rules.index'));
    $r1->refresh();
    $r2 = LoyaltyRule::orderByDesc('id')->first();
    expect($r1->active)->toBeFalse();
    expect($r2->active)->toBeTrue();
});
