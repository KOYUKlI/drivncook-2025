<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRatioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'warehouse']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'new_ratio' => 'required|numeric|min:0|max:100',
            'adjustment_reason' => 'required|string|max:500',
            'effective_date' => 'nullable|date|after_or_equal:today',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'new_ratio.required' => 'Le nouveau ratio est obligatoire.',
            'new_ratio.numeric' => 'Le ratio doit être un nombre.',
            'new_ratio.min' => 'Le ratio ne peut pas être négatif.',
            'new_ratio.max' => 'Le ratio ne peut pas dépasser 100.',
            'adjustment_reason.required' => 'La raison de l\'ajustement est obligatoire.',
            'adjustment_reason.max' => 'La raison ne peut pas dépasser 500 caractères.',
            'effective_date.date' => 'La date d\'effet doit être une date valide.',
            'effective_date.after_or_equal' => 'La date d\'effet ne peut pas être dans le passé.',
        ];
    }
}
