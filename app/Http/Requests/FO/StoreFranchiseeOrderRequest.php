<?php

namespace App\Http\Requests\FO;

use App\Models\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;

class StoreFranchiseeOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', PurchaseOrder::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'lines' => ['required','array','min:1'],
            'lines.*.stock_item_id' => ['required','exists:stock_items,id'],
            'lines.*.qty' => ['required','integer','min:1'],
        ];
    }
}
