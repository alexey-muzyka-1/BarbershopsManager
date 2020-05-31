<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use App\Model\User\UserErrorConstants;

class Role
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ALL_ROLES = [self::ROLE_USER, self::ROLE_ADMIN];

    private $name;

    public function __construct(string $name)
    {
        if (!in_array($name, [self::ROLE_USER, self::ROLE_ADMIN])) {
            throw new \DomainException(UserErrorConstants::USER_UNDEFINED_ROLE);
        }

        $this->name = $name;
    }

    public static function user(): self
    {
        return new self(self::ROLE_USER);
    }

    public static function admin(): self
    {
        return new self(self::ROLE_ADMIN);
    }

    public function isUser(): bool
    {
        return self::ROLE_USER === $this->name;
    }

    public function isAdmin(): bool
    {
        return self::ROLE_ADMIN === $this->name;
    }

    public function isEqual(self $role): bool
    {
        return $this->getName() === $role->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
