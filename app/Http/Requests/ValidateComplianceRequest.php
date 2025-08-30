<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateComplianceRequest extends FormRequest
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
            'compliance_status' => 'required|in:validated,rejected,needs_review',
            'inspector_notes' => 'nullable|string|max:1000',
            'corrective_actions' => 'nullable|string|max:1000',
            'reinspection_date' => 'nullable|date|after:today',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'compliance_status.required' => 'Le statut de conformité est obligatoire.',
            'compliance_status.in' => 'Le statut de conformité sélectionné n\'est pas valide.',
            'inspector_notes.max' => 'Les notes de l\'inspecteur ne peuvent pas dépasser 1000 caractères.',
            'corrective_actions.max' => 'Les actions correctives ne peuvent pas dépasser 1000 caractères.',
            'reinspection_date.date' => 'La date de réinspection doit être une date valide.',
            'reinspection_date.after' => 'La date de réinspection doit être future.',
        ];
    }
}
