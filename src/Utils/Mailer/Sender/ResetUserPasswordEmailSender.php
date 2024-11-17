<?php

declare(strict_types=1);

namespace App\Utils\Mailer\Sender;

use App\Entity\User;
use App\Service\SiteSettingsService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;

/**
 *
 */
class ResetUserPasswordEmailSender extends BaseSender
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
     * @param User               $user
     * @param ResetPasswordToken $resetPasswordToken
     *
     * @return void
     */
    public function sendEmailToClient(User $user, ResetPasswordToken $resetPasswordToken): void
    {
        $emailContext = [];

        $emailContext['resetToken'] = $resetPasswordToken;
        $emailContext['user'] = $user;
        $emailContext['profileUrl'] = $this->urlGenerator->generate(
            'main_profile_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $mailerOptions = $this->getMailerOptions()
                              ->setRecipient($user->getEmail())
                              ->setSubject('Запрос на сброс пароля - ' . $this->settingsService->getSiteName())
                              ->setHtmlTemplate('front/email/security/reset_password.html.twig')
                              ->setContext($emailContext);

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}
