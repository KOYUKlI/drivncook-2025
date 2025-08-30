<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Truck;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateTruckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $truck = $this->route('truck');
        return $this->user()->can('update', $truck);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $truck = $this->route('truck');

        $rules = [
            'name' => ['required', 'string', 'min:2'],
            'plate_number' => [
                'required', 
                'string', 
                'max:20', 
                Rule::unique('trucks', 'plate')->ignore($truck->id)
            ],
            'vin' => [
                'nullable', 
                'string', 
                'alpha_num', 
                'max:32', 
                Rule::unique('trucks', 'vin')->ignore($truck->id)
            ],
            'make' => ['nullable', 'string', 'max:50'],
            'model' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'integer', 'min:1980', 'max:2100'],
            'status' => ['required', 'in:draft,active,in_maintenance,retired'],
            'franchisee_id' => ['nullable', 'ulid', 'exists:franchisees,id'],
            'mileage_km' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'registration_doc' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'insurance_doc' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => __('ui.bo_trucks.fields.name'),
            'plate_number' => __('ui.bo_trucks.fields.plate_number'),
            'vin' => __('ui.bo_trucks.fields.vin'),
            'make' => __('ui.bo_trucks.fields.make'),
            'model' => __('ui.bo_trucks.fields.model'),
            'year' => __('ui.bo_trucks.fields.year'),
            'status' => __('ui.bo_trucks.fields.status'),
            'franchisee_id' => __('ui.bo_trucks.fields.franchisee'),
            'mileage_km' => __('ui.bo_trucks.fields.mileage_km'),
            'notes' => __('ui.bo_trucks.fields.notes'),
            'registration_doc' => __('ui.bo_trucks.fields.registration_doc'),
            'insurance_doc' => __('ui.bo_trucks.fields.insurance_doc'),
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Verify status transition is valid.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $truck = $this->route('truck');
            $currentStatus = $truck->status;
            $newStatus = $this->status;

            // Map UI status to model status
            $statusMap = [
                'draft' => 'Draft',
                'active' => 'Active',
                'in_maintenance' => 'InMaintenance',
                'retired' => 'Retired',
            ];
            
            $currentStatus = array_search($currentStatus, $statusMap) ?: $currentStatus;
            $newModelStatus = $statusMap[$newStatus] ?? $newStatus;

            $allowedTransitions = [
                'draft' => ['draft', 'active', 'in_maintenance', 'retired'],
                'active' => ['active', 'in_maintenance', 'retired'],
                'in_maintenance' => ['in_maintenance', 'active', 'retired'],
                'retired' => ['retired'],
            ];

            if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
                $validator->errors()->add('status', __('ui.flash.invalid_transition'));
                abort(Response::HTTP_CONFLICT, __('ui.flash.invalid_transition'));
            }
        });
    }
}
