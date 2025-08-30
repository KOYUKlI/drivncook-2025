<?php

namespace App\Http\Requests\MaintenanceLog;

use App\Models\MaintenanceLog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScheduleMaintenanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('schedule', MaintenanceLog::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'truck_id' => ['required', 'exists:trucks,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'severity' => [
                'required', 
                Rule::in([
                    MaintenanceLog::SEVERITY_LOW, 
                    MaintenanceLog::SEVERITY_MEDIUM, 
                    MaintenanceLog::SEVERITY_HIGH
                ])
            ],
            'priority' => [
                'required', 
                Rule::in([
                    MaintenanceLog::PRIORITY_P3, 
                    MaintenanceLog::PRIORITY_P2, 
                    MaintenanceLog::PRIORITY_P1
                ])
            ],
            'planned_start_at' => ['required', 'date', 'after_or_equal:today'],
            'planned_end_at' => ['required', 'date', 'after:planned_start_at'],
            'provider_name' => ['nullable', 'string', 'max:255'],
            'provider_contact' => ['nullable', 'string', 'max:255'],
            'provider_reference' => ['nullable', 'string', 'max:255'],
            'estimated_cost_amount' => ['nullable', 'numeric', 'min:0'],
            'estimated_cost_currency' => ['nullable', 'string', 'size:3'],
            'labor_cost_amount' => ['nullable', 'numeric', 'min:0'],
            'labor_cost_currency' => ['nullable', 'string', 'size:3'],
            'parts_cost_amount' => ['nullable', 'numeric', 'min:0'],
            'parts_cost_currency' => ['nullable', 'string', 'size:3'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'], // 10MB max per file
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'truck_id' => __('maintenance.truck'),
            'title' => __('maintenance.title'),
            'description' => __('maintenance.description'),
            'severity' => __('maintenance.severity'),
            'priority' => __('maintenance.priority'),
            'planned_start_at' => __('maintenance.planned_start_at'),
            'planned_end_at' => __('maintenance.planned_end_at'),
            'provider_name' => __('maintenance.provider_name'),
            'provider_contact' => __('maintenance.provider_contact'),
            'provider_reference' => __('maintenance.provider_reference'),
            'estimated_cost_amount' => __('maintenance.estimated_cost_amount'),
            'estimated_cost_currency' => __('maintenance.estimated_cost_currency'),
            'labor_cost_amount' => __('maintenance.labor_cost_amount'),
            'labor_cost_currency' => __('maintenance.labor_cost_currency'),
            'parts_cost_amount' => __('maintenance.parts_cost_amount'),
            'parts_cost_currency' => __('maintenance.parts_cost_currency'),
            'attachments' => __('maintenance.attachments'),
            'attachments.*' => __('maintenance.attachment'),
        ];
    }
}
