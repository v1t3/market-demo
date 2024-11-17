<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 */
abstract class BaseAdminController extends AbstractController
{
    /**
     * @return bool
     */
    protected function checkTheAccessLevel(): bool
    {
        /** @var User $user */
        $user = $this->getUser();

        if (false === $user->isVerified()) {
            $this->addFlash('danger', 'Недостаточно прав для просмотра страницы');

            return false;
        }

        return true;
    }
}
