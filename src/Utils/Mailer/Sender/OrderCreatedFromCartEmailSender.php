<?php

declare(strict_types=1);

namespace App\Utils\Mailer\Sender;

use App\Entity\Order;
use App\Entity\User;
use App\Service\SiteSettingsService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 *
 */
class OrderCreatedFromCartEmailSender extends BaseSender
{
    /**
     * @var SiteSettingsService
     */
    private SiteSettingsService $settingsService;

    /**
     * @param ParameterBagInterface $parameterBag
     * @param SiteSettingsService   $settingsService
     */
    public function __construct(ParameterBagInterface $parameterBag, SiteSettingsService $settingsService)
    {
        parent::__construct($parameterBag);
        $this->settingsService = $settingsService;
    }

    /**
     * @param Order $order
     *
     * @return void
     */
    public function sendEmailToClient(Order $order): void
    {
        /** @var User $user */
        $user = $order->getOwner();
        $mailerOptions = $this->getMailerOptions()
                              ->setRecipient($user->getEmail())
                              ->setCc($this->parameterBag->get('admin_email'))
                              ->setSubject('Ваш заказ оформлен - ' . $this->settingsService->getSiteName())
                              ->setHtmlTemplate('front/email/client/created_order_from_cart.html.twig')
                              ->setContext(
                                  [
                                      'order'      => $order,
                                      'profileUrl' => $this->urlGenerator->generate(
                                          'main_profile_index',
                                          [],
                                          UrlGeneratorInterface::ABSOLUTE_URL
                                      ),
                                      'site_name'  => $this->settingsService->getSiteName()
                                  ]
                              );

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }

    /**
     * @param Order $order
     *
     * @return void
     */
    public function sendEmailToManager(Order $order): void
    {
        $mailerOptions = $this->getMailerOptions()
                              ->setRecipient($this->parameterBag->get('admin_email'))
                              ->setSubject("Поступил заказ (ID: {$order->getId()})")
                              ->setHtmlTemplate('front/email/manager/created_order_from_cart.html.twig')
                              ->setContext(
                                  [
                                      'order'     => $order,
                                      'site_name' => $this->settingsService->getSiteName()
                                  ]
                              );

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}
