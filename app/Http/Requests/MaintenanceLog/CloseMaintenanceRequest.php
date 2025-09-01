<?php

namespace App\Http\Requests\MaintenanceLog;

use App\Models\MaintenanceLog;
use Illuminate\Foundation\Http\FormRequest;

class CloseMaintenanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $param = $this->route('maintenanceLog');
        $maintenanceLog = $param instanceof MaintenanceLog ? $param : MaintenanceLog::findOrFail($param);
        return $this->user()->can('close', $maintenanceLog);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'resolution' => ['required', 'string'],
            'odometer_reading' => ['required', 'numeric', 'min:0'],
            'labor_cost_amount' => ['nullable', 'numeric', 'min:0'],
            'labor_cost_currency' => ['nullable', 'string', 'size:3'],
            'parts_cost_amount' => ['nullable', 'numeric', 'min:0'],
            'parts_cost_currency' => ['nullable', 'string', 'size:3'],
            'additional_costs_amount' => ['nullable', 'numeric', 'min:0'],
            'additional_costs_currency' => ['nullable', 'string', 'size:3'],
            'additional_costs_description' => ['nullable', 'string'],
            'provider_invoice_reference' => ['nullable', 'string', 'max:255'],
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
            'resolution' => __('maintenance.resolution'),
            'odometer_reading' => __('maintenance.odometer_reading'),
            'labor_cost_amount' => __('maintenance.labor_cost_amount'),
            'labor_cost_currency' => __('maintenance.labor_cost_currency'),
            'parts_cost_amount' => __('maintenance.parts_cost_amount'),
            'parts_cost_currency' => __('maintenance.parts_cost_currency'),
            'additional_costs_amount' => __('maintenance.additional_costs_amount'),
            'additional_costs_currency' => __('maintenance.additional_costs_currency'),
            'additional_costs_description' => __('maintenance.additional_costs_description'),
            'provider_invoice_reference' => __('maintenance.provider_invoice_reference'),
            'attachments' => __('maintenance.attachments'),
            'attachments.*' => __('maintenance.attachment'),
        ];
    }
}
