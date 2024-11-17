<?php

declare(strict_types=1);

namespace App\Utils\Authenticator;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

/**
 *
 */
trait CheckingUserSocialNetworkBeforeAuthorization
{
    /**
     * @var Security
     */
    private Security $security;

    /**
     * @param Security $security
     *
     * @return CheckingUserSocialNetworkBeforeAuthorization
     */
    #[Required]
    public function setSecurity(Security $security): self
    {
        $this->security = $security;

        return $this;
    }

    /**
     * @param string $socialNetworkUserEmail
     *
     * @return bool
     */
    protected function checkingUserSocialNetworkBeforeAuthorization(string $socialNetworkUserEmail): bool
    {
        /** @var User|null $activeUser */
        $activeUser = $this->security->getUser();

        if ($activeUser) {
            $activeUserEmail = $activeUser->getEmail();

            if ($activeUserEmail !== $socialNetworkUserEmail) {
                return true;
            }
        }

        return false;
    }
}
