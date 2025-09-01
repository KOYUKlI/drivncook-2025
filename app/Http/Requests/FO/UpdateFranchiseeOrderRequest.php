<?php

namespace App\Http\Requests\FO;

use App\Models\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFranchiseeOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var PurchaseOrder|null $order */
        $order = $this->route('order');
        return $order ? ($this->user()?->can('update', $order) ?? false) : false;
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
