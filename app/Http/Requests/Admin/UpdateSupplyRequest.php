<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Supply;

class UpdateSupplyRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('update', $this->route('supply') ?? Supply::class) ?? false; }

    public function rules(): array
    {
        $supply = $this->route('supply');
        $id = $supply?->id ?? 'NULL';
        $units = implode(',', config('units.allowed', ['kg','g','L','ml','pc','pack']));
        return [
            'name' => 'required|string|max:255',
            'sku'  => 'nullable|string|max:190|unique:supplies,sku,'.$id,
            'unit' => 'nullable|in:'.$units,
            'cost' => 'nullable|numeric|min:0',
        ];
    }
}
