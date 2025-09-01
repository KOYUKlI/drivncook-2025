<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
    // Legacy purchase-orders routes removed; keep no rules here.
    return [];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'action.required' => 'Une action est requise.',
            'action.in' => 'L\'action doit être : approuver, signaler ou rejeter.',
            'override_reason.required_if' => 'Une raison est requise pour approuver une commande non-conforme.',
            'central_ratio.required' => 'Le ratio central est obligatoire.',
            'central_ratio.numeric' => 'Le ratio central doit être un nombre.',
            'central_ratio.min' => 'Le ratio central ne peut être négatif.',
            'central_ratio.max' => 'Le ratio central ne peut dépasser 100%.',
            'reason.required' => 'Une raison est obligatoire.',
            'message.max' => 'Le message ne peut dépasser 500 caractères.',
            'reason.max' => 'La raison ne peut dépasser 500 caractères.',
            'override_reason.max' => 'La raison de dérogation ne peut dépasser 500 caractères.',
        ];
    }
}
