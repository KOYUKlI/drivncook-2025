<?php

namespace App\Http\Requests\Replenishments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReplenishmentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin','warehouse']) ?? false;
    }

    public function rules(): array
    {
        $rules = [
            'status' => ['required','string','in:Approved,Picked,Shipped,Delivered,Closed,Cancelled'],
        ];

        if ($this->input('status') === 'Shipped') {
            $rules['ship'] = ['nullable','array'];
            $rules['ship.lines'] = ['nullable','array'];
            $rules['ship.lines.*.qty_shipped'] = ['nullable','integer','min:1'];
        }

        if ($this->input('status') === 'Delivered') {
            $rules['receive'] = ['nullable','array'];
            $rules['receive.lines'] = ['nullable','array'];
            $rules['receive.lines.*.qty_delivered'] = ['nullable','integer','min:1'];
        }

        return $rules;
    }
}
