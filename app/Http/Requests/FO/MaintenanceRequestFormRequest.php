<?php

namespace App\Http\Requests\FO;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceRequestFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('franchisee') && $this->user()->franchisee;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'type' => 'required|in:preventive,corrective',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // Max 5MB
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => __('ui.fo.maintenance_request.fields.title'),
            'description' => __('ui.fo.maintenance_request.fields.description'),
            'type' => __('ui.fo.maintenance_request.fields.type'),
            'attachment' => __('ui.fo.maintenance_request.fields.attachment'),
        ];
    }
}
