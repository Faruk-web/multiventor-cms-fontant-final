<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderPlaced
{
    use Dispatchable, SerializesModels;

    public Order $order;

   public function __construct(Order $order)
{
    $this->order = $order->load('user', 'orders_products.product');
}

}
