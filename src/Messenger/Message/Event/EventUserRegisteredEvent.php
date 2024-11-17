<?php

declare(strict_types=1);

namespace App\Messenger\Message\Event;

/**
 *
 */
class EventUserRegisteredEvent
{
    /**
     * @param int $userId
     */
    public function __construct(private int $userId)
    {
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
