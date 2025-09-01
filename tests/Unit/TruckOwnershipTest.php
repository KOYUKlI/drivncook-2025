<?php

use App\Models\Franchisee;
use App\Models\Truck;
use App\Models\TruckOwnership;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an ownership on truck create when franchisee is set', function() {
    $fr = Franchisee::factory()->create();
    $truck = Truck::factory()->create(['franchisee_id' => $fr->id]);

    expect(TruckOwnership::where('truck_id', $truck->id)->whereNull('ended_at')->count())->toBe(1);
});

it('ends previous ownership and creates new one when franchisee changes', function() {
    $fr1 = Franchisee::factory()->create();
    $fr2 = Franchisee::factory()->create();
    $truck = Truck::factory()->create(['franchisee_id' => $fr1->id]);

    $truck->update(['franchisee_id' => $fr2->id]);

    $active = TruckOwnership::where('truck_id', $truck->id)->whereNull('ended_at')->first();
    expect($active)->not()->toBeNull();
    expect($active->franchisee_id)->toBe($fr2->id);
    expect(TruckOwnership::where('truck_id', $truck->id)->whereNotNull('ended_at')->count())->toBe(1);
});

it('closes ownership when franchisee is set to null', function() {
    $fr = Franchisee::factory()->create();
    $truck = Truck::factory()->create(['franchisee_id' => $fr->id]);

    $truck->update(['franchisee_id' => null]);

    expect(TruckOwnership::where('truck_id', $truck->id)->whereNull('ended_at')->count())->toBe(0);
});
