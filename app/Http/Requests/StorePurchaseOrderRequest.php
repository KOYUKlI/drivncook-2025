<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) optional(Auth::user())->can('create', \App\Models\PurchaseOrder::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|ulid|exists:warehouses,id',
            'lines' => 'required|array|min:1',
            'lines.*.stock_item_id' => 'required|ulid|exists:stock_items,id',
            'lines.*.qty' => 'required|integer|min:1',
            'lines.*.unit_price_cents' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'warehouse_id.required' => 'L\'entrepôt est obligatoire.',
            'warehouse_id.exists' => 'L\'entrepôt sélectionné n\'existe pas.',
            'lines.required' => 'Au moins un article est requis.',
            'lines.min' => 'Au moins un article est requis.',
            'lines.*.stock_item_id.required' => 'L\'article est obligatoire.',
            'lines.*.stock_item_id.exists' => 'L\'article sélectionné n\'existe pas.',
            'lines.*.qty.required' => 'La quantité est obligatoire.',
            'lines.*.qty.min' => 'La quantité doit être d\'au moins 1.',
            'lines.*.unit_price_cents.required' => 'Le prix unitaire est obligatoire.',
            'lines.*.unit_price_cents.min' => 'Le prix unitaire ne peut pas être négatif.',
        ];
    }
}
