<?php

declare(strict_types=1);

namespace App\Utils\Manager;

use App\Entity\User;
use App\Exception\Security\EmptyUserPlainPasswordException;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 *
 */
final class UserManager extends AbstractBaseManager
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     *
     * @return $this
     */
    #[Required]
    public function setUserPasswordHasher(UserPasswordHasherInterface $userPasswordHasher): UserManager
    {
        $this->userPasswordHasher = $userPasswordHasher;

        return $this;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository
    {
        return $this->em->getRepository(User::class);
    }

    /**
     * @param User   $user
     * @param string $plainPassword
     *
     * @return void
     */
    public function encodePassword(User $user, string $plainPassword): void
    {
        $preparedPassword = trim($plainPassword);

        if (!$preparedPassword) {
            throw new EmptyUserPlainPasswordException('Empty user\'s password');
        }

        $hashPassword = $this->userPasswordHasher->hashPassword($user, $preparedPassword);
        $user->setPassword($hashPassword);
    }

    /**
     * @param object $entity
     *
     * @return void
     */
    public function remove(object $entity): void
    {
        /** @var User $user */
        $user = $entity;

        $this->em->persist($user);
        $user->setIsDeleted(true);
        $this->em->flush();
    }
}
