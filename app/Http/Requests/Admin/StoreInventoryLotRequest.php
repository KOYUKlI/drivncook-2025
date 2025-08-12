<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryLotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $inventory = $this->route('inventory');
        $invId = $inventory?->id ?? 0;
        return [
            'lot_code' => 'required|string|max:64|unique:inventory_lots,lot_code,NULL,id,inventory_id,'.$invId,
            'qty' => 'required|numeric|min:0.001',
            'expires_at' => 'nullable|date',
        ];
    }
}
