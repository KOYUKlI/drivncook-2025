<?php

namespace App\Observers;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderItemObserver
{
    protected function recompute(OrderItem $item): void
    {
        $orderId = $item->customer_order_id;
        $sum = DB::table('order_items')->where('customer_order_id',$orderId)
            ->selectRaw('SUM(quantity*price) as total')->value('total');
        DB::table('customer_orders')->where('id',$orderId)->update(['total_price' => $sum ?? 0]);
    }

    public function created(OrderItem $item): void { $this->recompute($item); }
    public function updated(OrderItem $item): void { $this->recompute($item); }
    public function deleted(OrderItem $item): void { $this->recompute($item); }
}
