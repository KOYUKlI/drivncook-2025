<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Supplier;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('update', $this->route('supplier') ?? Supplier::class) ?? false; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:190',
            'siret' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:190',
            'phone' => 'nullable|string|max:40',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
