<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\OrderCreatedFromCartEvent;
use App\Utils\Mailer\Sender\OrderCreatedFromCartEmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 *
 */
class OrderCreatedFromCartSendNotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var OrderCreatedFromCartEmailSender
     */
    private OrderCreatedFromCartEmailSender $orderCreatedFromCartEmailSender;

    /**
     * @param OrderCreatedFromCartEmailSender $orderCreatedFromCartEmailSender
     *
     * @return $this
     */
    #[Required]
    public function setOrderCreatedFromCartEmailSender(OrderCreatedFromCartEmailSender $orderCreatedFromCartEmailSender
    ): OrderCreatedFromCartSendNotificationSubscriber {
        $this->orderCreatedFromCartEmailSender = $orderCreatedFromCartEmailSender;

        return $this;
    }

    /**
     * @param OrderCreatedFromCartEvent $event
     *
     * @return void
     */
    public function onOrderCreatedFromCartEvent(OrderCreatedFromCartEvent $event): void
    {
        $order = $event->getOrder();

        $this->orderCreatedFromCartEmailSender->sendEmailToClient($order);
        $this->orderCreatedFromCartEmailSender->sendEmailToManager($order);
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OrderCreatedFromCartEvent::class => 'onOrderCreatedFromCartEvent',
        ];
    }
}
