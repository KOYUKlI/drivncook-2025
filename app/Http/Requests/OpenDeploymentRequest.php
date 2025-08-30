<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin','fleet']) ?? false;
    }

    public function rules(): array
    {
        return [
            'actual_start_at' => 'required|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'actual_start_at' => __('ui.deployment.fields.actual_start_at'),
        ];
    }
}
