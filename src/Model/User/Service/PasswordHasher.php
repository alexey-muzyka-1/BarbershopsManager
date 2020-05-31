<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\UserErrorConstants;
use RuntimeException;

class PasswordHasher
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2I);
        if (false === $hash) {
            throw new RuntimeException(UserErrorConstants::USER_HASH_ERROR);
        }

        return $hash;
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
