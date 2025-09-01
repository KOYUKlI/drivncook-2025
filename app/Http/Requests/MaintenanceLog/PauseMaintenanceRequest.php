<?php

namespace App\Http\Requests\MaintenanceLog;

use App\Models\MaintenanceLog;
use Illuminate\Foundation\Http\FormRequest;

class PauseMaintenanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $param = $this->route('maintenanceLog');
        $maintenanceLog = $param instanceof MaintenanceLog ? $param : MaintenanceLog::findOrFail($param);
        return $this->user()->can('pause', $maintenanceLog);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pause_reason' => ['required', 'string'],
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
            'pause_reason' => __('maintenance.pause_reason'),
            'attachments' => __('maintenance.attachments'),
            'attachments.*' => __('maintenance.attachment'),
        ];
    }
}
