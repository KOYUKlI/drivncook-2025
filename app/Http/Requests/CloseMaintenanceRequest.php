<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $log = \App\Models\MaintenanceLog::find($this->route('log'));
        return $log && $this->user()?->can('close', $log);
    }

    public function rules(): array
    {
        return [
            'resolution' => 'required|string|min:5',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'closed_at' => 'required|date|after_or_equal:opened_at',
            'opened_at' => 'required|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'resolution' => __('ui.maintenance.fields.resolution'),
            'attachment' => __('ui.maintenance.fields.attachment'),
            'closed_at' => __('ui.maintenance.fields.closed_at'),
            'opened_at' => __('ui.maintenance.fields.opened_at'),
        ];
    }
}
