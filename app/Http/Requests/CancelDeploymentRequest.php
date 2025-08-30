<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelDeploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin','fleet']) ?? false;
    }

    public function rules(): array
    {
        return [
            // No fields required for cancel; server enforces state
        ];
    }
}
