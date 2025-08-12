<?php

use App\Models\{Truck, Franchise, Location};
use App\Services\DeploymentService;
use Illuminate\Support\Facades\App;

it('prevents overlapping deployments for same truck', function () {
    $franchise = Franchise::factory()->create();
    $truck = Truck::factory()->create(['franchise_id' => $franchise->id]);
    $location = Location::first() ?? Location::create(['label' => 'Loc A']);

    /** @var DeploymentService $svc */
    $svc = App::make(DeploymentService::class);
    $start = now()->setTime(8,0,0);
    $svc->schedule($truck->id, $location->id, $start->toDateTimeString(), $start->copy()->addHours(4)->toDateTimeString());

    expect(fn() => $svc->schedule($truck->id, $location->id, $start->copy()->addHour()->toDateTimeString(), $start->copy()->addHours(5)->toDateTimeString()))
        ->toThrow(Exception::class); // abort(422) -> HttpException
});
