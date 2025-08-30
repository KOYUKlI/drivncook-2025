<?php

namespace App\Http\Requests\TruckDeployment;

use Illuminate\Foundation\Http\FormRequest;

class CloseDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('close', $this->route('deployment')) ?? false;
    }

    public function rules(): array
    {
        $deployment = $this->route('deployment');
        
        return [
            'actual_start_at' => 'sometimes|required|date',
            'actual_end_at' => [
                'required', 
                'date', 
                'after_or_equal:' . ($deployment->actual_start_at ?? 'actual_start_at')
            ],
            'notes' => 'sometimes|nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'actual_start_at' => __('deployment.fields.actual_start_at'),
            'actual_end_at' => __('deployment.fields.actual_end_at'),
            'notes' => __('deployment.fields.notes'),
        ];
    }
}
