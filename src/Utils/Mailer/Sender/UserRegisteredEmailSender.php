<?php

declare(strict_types=1);

namespace App\Utils\Mailer\Sender;

use App\Entity\User;
use App\Service\SiteSettingsService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

/**
 *
 */
class UserRegisteredEmailSender extends BaseSender
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
     * @param User                           $user
     * @param VerifyEmailSignatureComponents $signatureComponents
     *
     * @return void
     */
    public function sendEmailToClient(User $user, VerifyEmailSignatureComponents $signatureComponents): void
    {
        $emailContext = [];

        $emailContext['signedUrl'] = $signatureComponents->getSignedUrl();
        $emailContext['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $emailContext['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();
        $emailContext['user'] = $user;
        $emailContext['profileUrl'] = $this->urlGenerator->generate(
            'main_profile_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $mailerOptions = $this->getMailerOptions()
                              ->setRecipient($user->getEmail())
                              ->setSubject(
                                  'Пожалуйста подтвердите свой email - ' . $this->settingsService->getSiteName()
                              )
                              ->setHtmlTemplate('front/email/security/confirmation_email.html.twig')
                              ->setContext($emailContext);

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}
