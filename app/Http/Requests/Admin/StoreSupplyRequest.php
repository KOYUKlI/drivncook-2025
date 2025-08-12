<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplyRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('create', \App\Models\Supply::class) ?? false; }

    public function rules(): array
    {
        $units = implode(',', config('units.allowed', ['kg','g','L','ml','pc','pack']));
        return [
            'name' => 'required|string|max:255',
            'sku'  => 'nullable|string|max:190|unique:supplies,sku',
            'unit' => 'nullable|in:'.$units,
            'cost' => 'nullable|numeric|min:0',
        ];
    }
}
