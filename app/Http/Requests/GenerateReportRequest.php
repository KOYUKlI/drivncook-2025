<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'warehouse', 'franchisee']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'report_type' => 'required|in:sales,purchase_orders,trucks,compliance',
            'period_type' => 'required|in:daily,weekly,monthly,quarterly,yearly,custom',
            'start_date' => 'nullable|date|required_if:period_type,custom',
            'end_date' => 'nullable|date|after_or_equal:start_date|required_if:period_type,custom',
            'format' => 'required|in:pdf,excel,csv',
            'include_charts' => 'nullable|boolean',
            'franchisee_filter' => 'nullable|array',
            'franchisee_filter.*' => 'string|max:26', // ULID length
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'report_type.required' => 'Le type de rapport est obligatoire.',
            'report_type.in' => 'Le type de rapport sélectionné n\'est pas valide.',
            'period_type.required' => 'La période est obligatoire.',
            'period_type.in' => 'La période sélectionnée n\'est pas valide.',
            'start_date.required_if' => 'La date de début est obligatoire pour une période personnalisée.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'end_date.required_if' => 'La date de fin est obligatoire pour une période personnalisée.',
            'end_date.date' => 'La date de fin doit être une date valide.',
            'end_date.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'format.required' => 'Le format est obligatoire.',
            'format.in' => 'Le format sélectionné n\'est pas valide.',
            'franchisee_filter.array' => 'Le filtre franchisé doit être une liste.',
            'franchisee_filter.*.max' => 'L\'ID du franchisé n\'est pas valide.',
        ];
    }
}
