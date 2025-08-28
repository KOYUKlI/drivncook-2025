<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleTruckDeploymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'fleet']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'franchisee_id' => 'required|string|max:26', // ULID length
            'territory' => 'required|string|max:255',
            'deployment_date' => 'required|date|after:today',
            'end_date' => 'nullable|date|after:deployment_date',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'franchisee_id.required' => 'Le franchisé est obligatoire.',
            'franchisee_id.max' => 'L\'ID du franchisé n\'est pas valide.',
            'territory.required' => 'Le territoire est obligatoire.',
            'territory.max' => 'Le territoire ne peut pas dépasser 255 caractères.',
            'deployment_date.required' => 'La date de déploiement est obligatoire.',
            'deployment_date.date' => 'La date de déploiement doit être une date valide.',
            'deployment_date.after' => 'La date de déploiement doit être future.',
            'end_date.date' => 'La date de fin doit être une date valide.',
            'end_date.after' => 'La date de fin doit être postérieure à la date de déploiement.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
        ];
    }
}
