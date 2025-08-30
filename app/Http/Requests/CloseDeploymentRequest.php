<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin','fleet']) ?? false;
    }

    public function rules(): array
    {
        return [
            'actual_end_at' => 'required|date|after_or_equal:actual_start_at',
            'actual_start_at' => 'required|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'actual_end_at' => __('ui.deployment.fields.actual_end_at'),
            'actual_start_at' => __('ui.deployment.fields.actual_start_at'),
        ];
    }
}
