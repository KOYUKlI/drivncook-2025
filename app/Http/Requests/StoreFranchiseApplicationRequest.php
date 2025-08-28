<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFranchiseApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public endpoint
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'territory' => 'required|string|max:255',
            'experience' => 'nullable|string|max:1000',
            'capital' => 'nullable|integer|min:0',
            'motivation' => 'nullable|string|max:2000',

            // Document uploads
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'identity' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
            'financial_statement' => 'nullable|file|mimes:pdf,xls,xlsx|max:10240',
            'motivation_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom de famille est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'territory.required' => 'Le territoire souhaité est obligatoire.',
            'capital.integer' => 'Le capital doit être un nombre entier.',
            'capital.min' => 'Le capital ne peut pas être négatif.',

            'cv.required' => 'Le CV est obligatoire.',
            'cv.mimes' => 'Le CV doit être au format PDF, DOC ou DOCX.',
            'cv.max' => 'Le CV ne doit pas dépasser 10 MB.',

            'identity.required' => 'La pièce d\'identité est obligatoire.',
            'identity.mimes' => 'La pièce d\'identité doit être au format PDF, JPG, JPEG ou PNG.',
            'identity.max' => 'La pièce d\'identité ne doit pas dépasser 5 MB.',

            'financial_statement.mimes' => 'Le bilan financier doit être au format PDF, XLS ou XLSX.',
            'financial_statement.max' => 'Le bilan financier ne doit pas dépasser 10 MB.',

            'motivation_letter.mimes' => 'La lettre de motivation doit être au format PDF, DOC ou DOCX.',
            'motivation_letter.max' => 'La lettre de motivation ne doit pas dépasser 5 MB.',
        ];
    }
}
