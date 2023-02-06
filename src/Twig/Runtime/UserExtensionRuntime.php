<?php

namespace App\Twig\Runtime;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\RuntimeExtensionInterface;

class UserExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    public function isFollowed(User $followed)
    {
        if (!$this->security->getUser()) {
            return false;
        }

        return $this->security->getUser()->isFollower($followed);
    }

    public function isBlocked(User $blocked)
    {
        if (!$this->security->getUser()) {
            return false;
        }

        return $this->security->getUser()->isBlocked($blocked);
    }

    public function username($value): string
    {
        return ltrim($value, '@');
    }
}
