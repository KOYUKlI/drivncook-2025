<?php

namespace App\Http\Requests\Franchise;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockOrderRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('create', \App\Models\StockOrder::class) ?? false; }

    public function rules(): array
    {
        return [
            'truck_id'     => 'required|exists:trucks,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'supplier_id'  => 'nullable|exists:suppliers,id',
            'status'       => 'sometimes|in:pending,approved,completed,canceled',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $w = $this->input('warehouse_id');
            $s = $this->input('supplier_id');
            if (!$w && !$s) {
                $validator->errors()->add('warehouse_id', 'Select a warehouse or a supplier');
                $validator->errors()->add('supplier_id', 'Select a warehouse or a supplier');
            }
            if ($w && $s) {
                $validator->errors()->add('warehouse_id', 'Choose only one target');
                $validator->errors()->add('supplier_id', 'Choose only one target');
            }
        });
    }
}
