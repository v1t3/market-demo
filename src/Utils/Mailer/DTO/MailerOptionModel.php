<?php

declare(strict_types=1);

namespace App\Utils\Mailer\DTO;

/**
 *
 */
class MailerOptionModel
{
    /**
     * @param string      $recipient
     * @param string|null $cc
     * @param string      $subject
     * @param string      $htmlTemplate
     * @param array       $context
     * @param string      $text
     */
    public function __construct(
        private string $recipient = '',
        private ?string $cc = null,
        private string $subject = '',
        private string $htmlTemplate = '',
        private array $context = [],
        private string $text = ''
    ) {
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     *
     * @return $this
     */
    public function setRecipient(string $recipient): MailerOptionModel
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCc(): ?string
    {
        return $this->cc;
    }

    /**
     * @param string $cc
     *
     * @return $this
     */
    public function setCc(string $cc): MailerOptionModel
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject(string $subject): MailerOptionModel
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlTemplate(): string
    {
        return $this->htmlTemplate;
    }

    /**
     * @param string $htmlTemplate
     *
     * @return $this
     */
    public function setHtmlTemplate(string $htmlTemplate): MailerOptionModel
    {
        $this->htmlTemplate = $htmlTemplate;

        return $this;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     *
     * @return $this
     */
    public function setContext(array $context): MailerOptionModel
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText(string $text): MailerOptionModel
    {
        $this->text = $text;

        return $this;
    }
}
