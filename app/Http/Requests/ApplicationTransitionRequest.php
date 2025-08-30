<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationTransitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeName = $this->route()->getName();

        return match (true) {
            str_contains($routeName, 'prequalify') => [
                'message' => 'nullable|string|max:500',
            ],
            str_contains($routeName, 'interview') => [
                'message' => 'nullable|string|max:500',
                'interview_date' => 'nullable|date|after:today',
            ],
            str_contains($routeName, 'approve') => [
                'message' => 'nullable|string|max:500',
            ],
            str_contains($routeName, 'reject') => [
                'reason' => 'required|string|max:500',
            ],
            default => [],
        };
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'reason.required' => 'La raison du rejet est obligatoire.',
            'reason.max' => 'La raison ne peut pas dépasser 500 caractères.',
            'interview_date.after' => 'La date d\'entretien doit être future.',
            'interview_date.date' => 'La date d\'entretien doit être une date valide.',
            'message.max' => 'Le message ne peut pas dépasser 500 caractères.',
        ];
    }
}
