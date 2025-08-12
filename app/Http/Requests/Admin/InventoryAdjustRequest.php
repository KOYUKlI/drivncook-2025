<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAdjustRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('adjust', \App\Models\Inventory::class) ?? false; }

    public function rules(): array
    {
        return [
            'inventory_id' => 'required|exists:inventory,id',
            'qty_diff' => 'required|numeric|not_in:0',
            'reason' => 'required|in:waste,breakage,audit',
            'note' => 'nullable|string|max:255',
        ];
    }
}
