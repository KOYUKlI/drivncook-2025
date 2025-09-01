<?php

namespace App\Http\Requests\TruckDeployment;

use App\Models\TruckDeployment;
use Illuminate\Foundation\Http\FormRequest;

class CancelDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $deployment = $this->route('deployment');
        
        if (!$deployment instanceof TruckDeployment) {
            return false;
        }
        
        return $this->user()?->can('cancel', $deployment) ?? false;
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
