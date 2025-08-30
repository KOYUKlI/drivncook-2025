<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTruckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Truck::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2'],
            'plate_number' => ['required', 'string', 'max:20', 'unique:trucks,plate'],
            'vin' => ['nullable', 'string', 'alpha_num', 'max:32', 'unique:trucks,vin'],
            'make' => ['nullable', 'string', 'max:50'],
            'model' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'integer', 'min:1980', 'max:2100'],
            'status' => ['required', 'in:draft,active,in_maintenance,retired'],
            'franchisee_id' => ['nullable', 'ulid', 'exists:franchisees,id'],
            'acquired_at' => ['nullable', 'date'],
            'commissioned_at' => ['nullable', 'date'],
            'mileage_km' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'registration_doc' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'insurance_doc' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }

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
            'acquired_at' => __('ui.bo_trucks.fields.acquired_at'),
            'commissioned_at' => __('ui.bo_trucks.fields.commissioned_at'),
            'mileage_km' => __('ui.bo_trucks.fields.mileage_km'),
            'notes' => __('ui.bo_trucks.fields.notes'),
            'registration_doc' => __('ui.bo_trucks.fields.registration_doc'),
            'insurance_doc' => __('ui.bo_trucks.fields.insurance_doc'),
        ];
    }
}
