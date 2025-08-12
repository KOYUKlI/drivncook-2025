<?php

namespace App\Http\Requests\Franchise;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\StockOrder;

class StoreStockOrderItemRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('update', $this->route('stockorder') ?? StockOrder::class) ?? false; }

    public function rules(): array
    {
        return [
            'supply_id' => 'required|exists:supplies,id',
            'quantity'  => 'required|integer|min:1',
        ];
    }
}
