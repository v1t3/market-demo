<?php

declare(strict_types=1);

namespace App\Messenger\Message\Command;

/**
 *
 */
class ResetUserPasswordCommand
{
    /**
     * @param string $email
     */
    public function __construct(private string $email)
    {
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
