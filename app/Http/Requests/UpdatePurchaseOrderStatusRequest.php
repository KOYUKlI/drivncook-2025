<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin', 'warehouse']) ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:draft,approved,prepared,shipped,received,cancelled',
        ];
    }
}
