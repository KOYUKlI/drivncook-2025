<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\MaintenanceLog::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:preventive,corrective',
            'description' => 'required|string|min:5',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'cost_cents' => 'nullable|integer|min:0',
            'opened_at' => 'required|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => __('ui.maintenance.type.label'),
            'description' => __('ui.maintenance.fields.description'),
            'attachment' => __('ui.maintenance.fields.attachment'),
            'cost_cents' => __('ui.maintenance.fields.cost_cents'),
            'opened_at' => __('ui.maintenance.fields.opened_at'),
        ];
    }
}
