<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCustomerOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'truck_id' => 'required|exists:trucks,id',
            'order_type' => 'required|in:online,on_site,reservation',
            'pickup_at' => 'nullable|date',
            'location_id' => 'nullable|exists:locations,id',
            'status' => 'sometimes|in:pending,confirmed,preparing,ready,completed,canceled',
            'payment_status' => 'sometimes|in:pending,paid,failed,refunded',
            'reference' => 'nullable|string|max:30',
            'items' => 'required|array|min:1',
            'items.*.dish_id' => 'required|exists:dishes,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function(Validator $v){
            $orderType = $this->input('order_type');
            $pickup = $this->input('pickup_at');
            $status = $this->input('status');
            $paymentStatus = $this->input('payment_status');
            if ($orderType === 'reservation' && !$pickup) {
                $v->errors()->add('pickup_at','pickup_at required for reservation');
            }
            if ($orderType === 'online' && !$this->input('reference')) {
                $v->errors()->add('reference','reference required for online order');
            }
            if ($status === 'completed' && $paymentStatus !== 'paid') {
                $v->errors()->add('status','Cannot mark completed unless payment_status is paid');
            }
        });
    }
}
