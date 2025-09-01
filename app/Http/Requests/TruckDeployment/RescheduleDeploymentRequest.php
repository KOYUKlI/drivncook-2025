<?php

namespace App\Http\Requests\TruckDeployment;

use App\Models\TruckDeployment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class RescheduleDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $deployment = $this->route('deployment');
        
        if (!$deployment instanceof TruckDeployment) {
            return false;
        }
        
        return $this->user()?->can('reschedule', $deployment) ?? false;
    }

    public function rules(): array
    {
        return [
            'planned_start_at' => 'required|date',
            'planned_end_at' => 'required|date|after_or_equal:planned_start_at',
            'location_text' => 'sometimes|string|min:2',
            'notes' => 'sometimes|nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $deployment = $this->route('deployment');
            
            // Check for conflicts with other deployments
            $startAt = Carbon::parse($this->planned_start_at);
            $endAt = Carbon::parse($this->planned_end_at);
            
            if (TruckDeployment::hasConflict(
                $deployment->truck_id, 
                $startAt, 
                $endAt, 
                $deployment->id
            )) {
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
            'planned_start_at' => __('deployment.fields.planned_start_at'),
            'planned_end_at' => __('deployment.fields.planned_end_at'),
            'location_text' => __('deployment.fields.location'),
            'notes' => __('deployment.fields.notes'),
        ];
    }
}
