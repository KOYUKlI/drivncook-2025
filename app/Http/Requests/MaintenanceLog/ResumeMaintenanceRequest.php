<?php

namespace App\Http\Requests\MaintenanceLog;

use App\Models\MaintenanceLog;
use Illuminate\Foundation\Http\FormRequest;

class ResumeMaintenanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $param = $this->route('maintenanceLog');
        $maintenanceLog = $param instanceof MaintenanceLog ? $param : MaintenanceLog::findOrFail($param);
        return $this->user()->can('resume', $maintenanceLog);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'resume_notes' => ['nullable', 'string'],
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
            'resume_notes' => __('maintenance.resume_notes'),
            'attachments' => __('maintenance.attachments'),
            'attachments.*' => __('maintenance.attachment'),
        ];
    }
}
