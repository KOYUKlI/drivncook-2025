<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('updateStatus', $this->purchaseOrder);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Different rules based on action type
        switch ($this->route('action')) {
            case 'prepare':
                return [
                    'notes' => 'nullable|string|max:1000',
                ];
            case 'ready':
                return [
                    'notes' => 'nullable|string|max:1000',
                    'shipping_date' => 'nullable|date',
                ];
            case 'ship':
                return [
                    'notes' => 'nullable|string|max:1000',
                    'tracking_number' => 'nullable|string|max:100',
                    'carrier' => 'nullable|string|max:100',
                ];
            case 'receive':
                return [
                    'notes' => 'nullable|string|max:1000',
                    'received_lines' => 'required|array',
                    'received_lines.*.line_id' => 'required|string|exists:purchase_lines,id',
                    'received_lines.*.received_qty' => 'required|integer|min:0',
                ];
            default:
                return [];
        }
    }
}
