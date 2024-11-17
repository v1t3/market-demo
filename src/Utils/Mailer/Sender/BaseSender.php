<?php

namespace App\Utils\Mailer\Sender;

use App\Utils\Mailer\DTO\MailerOptionModel;
use App\Utils\Mailer\MailerSender;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 *
 */
abstract class BaseSender
{
    /**
     * @var MailerSender
     */
    protected MailerSender $mailerSender;

    /**
     * @param MailerSender $mailerSender
     *
     * @return $this
     */
    #[Required]
    public function setMailerSender(MailerSender $mailerSender): BaseSender
    {
        $this->mailerSender = $mailerSender;

        return $this;
    }

    /**
     * @var UrlGeneratorInterface
     */
    protected UrlGeneratorInterface $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     *
     * @return $this
     */
    #[Required]
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator): BaseSender
    {
        $this->urlGenerator = $urlGenerator;

        return $this;
    }

    /**
     * @var ParameterBagInterface
     */
    protected ParameterBagInterface $parameterBag;

    /**
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @return MailerOptionModel
     */
    protected function getMailerOptions(): MailerOptionModel
    {
        return new MailerOptionModel();
    }
}
