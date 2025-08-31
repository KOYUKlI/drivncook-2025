<?php

namespace App\Http\Requests\TruckDeployment;

use App\Models\TruckDeployment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ScheduleDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', TruckDeployment::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'location_text' => 'required|string|min:2',
            'planned_start_at' => 'required|date',
            'planned_end_at' => 'required|date|after_or_equal:planned_start_at',
            'franchisee_id' => 'nullable|exists:franchisees,id',
            'notes' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $truckId = $this->route('truck')->id;
            
            // Check for conflicts with other deployments
            $startAt = Carbon::parse($this->planned_start_at);
            $endAt = Carbon::parse($this->planned_end_at);
            
            if (TruckDeployment::hasConflict($truckId, $startAt, $endAt)) {
                $validator->errors()->add(
                    'planned_start_at', 
                    __('deployment.errors.schedule_conflict')
                );
            }
        });
    }

    public function attributes(): array
    {
        return [
            'location_text' => __('deployment.fields.location'),
            'planned_start_at' => __('deployment.fields.planned_start_at'),
            'planned_end_at' => __('deployment.fields.planned_end_at'),
            'franchisee_id' => __('deployment.fields.franchisee'),
            'notes' => __('deployment.fields.notes'),
        ];
    }
}
