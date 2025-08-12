<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InventoryMoveRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('move', \App\Models\Inventory::class) ?? false; }

    public function rules(): array
    {
        return [
            'from_inventory_id' => 'required|exists:inventory,id',
            'to_inventory_id' => 'required|exists:inventory,id|different:from_inventory_id',
            'qty' => 'required|numeric|min:0.001',
        ];
    }
}
