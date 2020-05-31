<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User\DoctrineType;

use App\Model\User\Entity\User\Role;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class RoleType extends StringType
{
    public const NAME = 'user_role';

    public function convertToDatabaseValue($roleName, AbstractPlatform $platform)
    {
        return $roleName instanceof Role ? $roleName->getName() : $roleName;
    }

    public function convertToPHPValue($roleName, AbstractPlatform $platform)
    {
        return !empty($roleName) ? new Role($roleName) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
