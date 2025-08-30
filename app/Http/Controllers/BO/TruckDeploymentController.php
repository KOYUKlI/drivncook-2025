<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleDeploymentRequest;
use App\Http\Requests\OpenDeploymentRequest;
use App\Http\Requests\CloseDeploymentRequest;
use App\Http\Requests\CancelDeploymentRequest;
use App\Models\Truck;
use App\Models\TruckDeployment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TruckDeploymentController extends Controller
{
    public function schedule(Truck $truck, ScheduleDeploymentRequest $request)
    {
        $this->authorize('create', TruckDeployment::class);

        $data = $request->validated();

        $deployment = TruckDeployment::create([
            'truck_id' => $truck->id,
            'franchisee_id' => $data['franchisee_id'] ?? null,
            'location_text' => $data['location_text'],
            'planned_start_at' => $data['planned_start_at'] ?? null,
            'planned_end_at' => $data['planned_end_at'] ?? null,
            'status' => 'planned',
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', __('ui.flash.deployment_scheduled'));
    }

    public function open(string $deployment, OpenDeploymentRequest $request)
    {
        $deployment = TruckDeployment::findOrFail($deployment);
        $this->authorize('open', $deployment);

        if ($deployment->status !== 'planned') {
            return response()->json(['message' => __('ui.flash.invalid_transition')], Response::HTTP_CONFLICT);
        }

        $deployment->status = 'open';
        $deployment->actual_start_at = $request->validated()['actual_start_at'];
        $deployment->save();

        return back()->with('success', __('ui.flash.deployment_opened'));
    }

    public function close(string $deployment, CloseDeploymentRequest $request)
    {
        $deployment = TruckDeployment::findOrFail($deployment);
        $this->authorize('close', $deployment);

        if ($deployment->status !== 'open') {
            return response()->json(['message' => __('ui.flash.invalid_transition')], Response::HTTP_CONFLICT);
        }

        $data = $request->validated();
        $deployment->status = 'closed';
        $deployment->actual_end_at = $data['actual_end_at'];
        // persist actual_start_at if posted (for validation check continuity)
        if (!$deployment->actual_start_at && !empty($data['actual_start_at'])) {
            $deployment->actual_start_at = $data['actual_start_at'];
        }
        $deployment->save();

        return back()->with('success', __('ui.flash.deployment_closed'));
    }

    public function cancel(string $deployment, CancelDeploymentRequest $request)
    {
        $deployment = TruckDeployment::findOrFail($deployment);
        $this->authorize('cancel', $deployment);

        if (!in_array($deployment->status, ['planned'])) {
            return response()->json(['message' => __('ui.flash.invalid_transition')], Response::HTTP_CONFLICT);
        }

        $deployment->status = 'cancelled';
        $deployment->save();

        return back()->with('success', __('ui.flash.deployment_cancelled'));
    }
}
