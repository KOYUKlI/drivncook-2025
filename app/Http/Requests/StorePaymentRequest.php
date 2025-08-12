<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:card,cash,voucher',
            'provider_ref' => 'nullable|string|max:100',
            'provider_ref' => 'nullable|string|max:100',
            'status' => 'sometimes|in:pending,captured,failed,refunded',
        ];
    }
}
