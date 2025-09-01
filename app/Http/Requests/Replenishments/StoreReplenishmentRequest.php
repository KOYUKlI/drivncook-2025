<?php

namespace App\Http\Requests\Replenishments;

use Illuminate\Foundation\Http\FormRequest;

class StoreReplenishmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin','warehouse']) ?? false;
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => ['required','string','exists:warehouses,id'],
            'franchisee_id' => ['required','string','exists:franchisees,id'],
            'lines' => ['required','array','min:1'],
            'lines.*.stock_item_id' => ['required','string','exists:stock_items,id'],
            'lines.*.qty' => ['required','integer','min:1'],
            'lines.*.unit_price_cents' => ['required','integer','min:0'],
        ];
    }
}
