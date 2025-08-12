<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryLotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $lot = $this->route('lot');
        $inventory = $this->route('inventory');
        $invId = $inventory?->id ?? $lot?->inventory_id;
        $lotId = $lot?->id ?? 0;
        return [
            'lot_code' => 'required|string|max:64|unique:inventory_lots,lot_code,'.$lotId.',id,inventory_id,'.$invId,
            'qty' => 'required|numeric|min:0.001',
            'expires_at' => 'nullable|date',
        ];
    }
}
