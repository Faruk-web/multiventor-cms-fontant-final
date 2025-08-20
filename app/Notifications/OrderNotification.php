<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderNotification extends Notification
{
     protected $order;
    protected $productName;

    // Constructor
    public function __construct(Order $order, $productName)
    {
        $this->order = $order;
        $this->productName = $productName;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message'      => "{$this->order->user->name}",
            'order_id'     => $this->order->id,
            'product_name' => $this->productName,
        ];
    }
}

