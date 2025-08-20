<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\OrderNotification;

class NotifyFollowers
{
    public function handle(OrderPlaced $event)
    {
        $order    = $event->order->load('orders_products.product', 'user'); // ensure eager loading
        $customer = $order->user;

        if (!$customer || $customer->followers->isEmpty()) {
            return;
        }

        $followers = $customer->followers;

      $order->load('orders_products.product');

foreach ($order->orders_products as $orderProduct) {
    $productName = $orderProduct->product?->name ?? 'Unknown Product';

    foreach ($followers as $follower) {
        $follower->notify(new OrderNotification($order, $productName));
        \Log::info('Notification sent to user '.$follower->id.' for '.$productName);
    }
}

    }
}
