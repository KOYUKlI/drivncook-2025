<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\TruckDeployment::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'location_text' => 'required|string|min:2',
            'planned_start_at' => 'nullable|date',
            'planned_end_at' => 'nullable|date|after_or_equal:planned_start_at',
            'franchisee_id' => 'nullable|ulid|exists:franchisees,id',
            'notes' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'location_text' => __('ui.deployment.fields.location'),
            'planned_start_at' => __('ui.deployment.fields.planned_start_at'),
            'planned_end_at' => __('ui.deployment.fields.planned_end_at'),
            'actual_start_at' => __('ui.deployment.fields.actual_start_at'),
            'actual_end_at' => __('ui.deployment.fields.actual_end_at'),
            'notes' => __('ui.deployment.fields.notes'),
        ];
    }
}
