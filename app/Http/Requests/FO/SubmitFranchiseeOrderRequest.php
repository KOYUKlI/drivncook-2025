<?php

namespace App\Http\Requests\FO;

use App\Models\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;

class SubmitFranchiseeOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var PurchaseOrder|null $order */
        $order = $this->route('order');
        return $order ? ($this->user()?->can('submit', $order) ?? false) : false;
    }

    public function rules(): array
    {
        return [];
    }
}
