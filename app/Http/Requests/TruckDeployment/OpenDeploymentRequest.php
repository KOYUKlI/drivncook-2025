<?php

namespace App\Http\Requests\TruckDeployment;

use App\Models\TruckDeployment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class OpenDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('open', $this->route('deployment')) ?? false;
    }

    public function rules(): array
    {
        return [
            'actual_start_at' => 'required|date',
            'geo_lat' => 'sometimes|nullable|numeric|between:-90,90',
            'geo_lng' => 'sometimes|nullable|numeric|between:-180,180',
            'location_text' => 'sometimes|string|min:2',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $deployment = $this->route('deployment');
            
            // Validate the truck is not already deployed elsewhere
            $startAt = Carbon::parse($this->actual_start_at);
            $endAt = $deployment->planned_end_at ?? $startAt->copy()->addDays(1);
            
            if (TruckDeployment::hasConflict(
                $deployment->truck_id, 
                $startAt, 
                $endAt, 
                $deployment->id
            )) {
                $validator->errors()->add(
                    'actual_start_at', 
                    __('deployment.errors.truck_already_deployed')
                );
            }
        });
    }

    public function attributes(): array
    {
        return [
            'actual_start_at' => __('deployment.fields.actual_start_at'),
            'geo_lat' => __('deployment.fields.geo_lat'),
            'geo_lng' => __('deployment.fields.geo_lng'),
            'location_text' => __('deployment.fields.location'),
        ];
    }
}
