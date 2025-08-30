<?php

namespace App\Http\Requests\TruckDeployment;

use Illuminate\Foundation\Http\FormRequest;

class CancelDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('cancel', $this->route('deployment')) ?? false;
    }

    public function rules(): array
    {
        return [
            'cancel_reason' => 'required|string|min:3|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'cancel_reason' => __('deployment.fields.cancel_reason'),
        ];
    }
}
