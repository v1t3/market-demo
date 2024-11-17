<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;

/**
 *
 */
class OrderCreatedFromCartEvent extends Event
{
    /**
     * @param Order $order
     */
    public function __construct(private Order $order)
    {
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }
}
