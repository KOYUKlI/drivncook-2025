<?php

namespace App\Services;

use App\Models\TruckDeployment;
use Illuminate\Support\Facades\DB;

class DeploymentService
{
    public function schedule(int $truckId, int $locationId, string $startsAt, ?string $endsAt = null): TruckDeployment
    {
        return DB::transaction(function () use ($truckId,$locationId,$startsAt,$endsAt) {
            $overlap = TruckDeployment::where('truck_id',$truckId)
                ->where(function($q) use ($startsAt,$endsAt) {
                    $rangeEnd = $endsAt ?? $startsAt;
                    $q->where(function($qq) use ($startsAt,$rangeEnd) {
                        $qq->whereNull('ends_at')->where('starts_at','<=',$rangeEnd);
                    })->orWhere(function($qq) use ($startsAt,$rangeEnd) {
                        $qq->whereNotNull('ends_at')
                           ->where(function($qqq) use ($startsAt,$rangeEnd){
                               $qqq->whereBetween('starts_at', [$startsAt,$rangeEnd])
                                   ->orWhereBetween('ends_at', [$startsAt,$rangeEnd])
                                   ->orWhere(function($q4) use ($startsAt,$rangeEnd){
                                       $q4->where('starts_at','<=',$startsAt)->where('ends_at','>=',$rangeEnd);
                                   });
                           });
                    });
                })->lockForUpdate()->exists();
            if ($overlap) {
                abort(422,'Deployment overlap detected');
            }
            return TruckDeployment::create([
                'truck_id'=>$truckId,
                'location_id'=>$locationId,
                'starts_at'=>$startsAt,
                'ends_at'=>$endsAt,
            ]);
        });
    }
}
