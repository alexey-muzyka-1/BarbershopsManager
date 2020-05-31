<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class UserTestHelper
{
    public static function createInactiveUserByEmail(Email $email = null): User
    {
        return User::signUpByEmail(
            Id::next(),
            $email ?: new Email('test@gmail.com'),
            new Name('Joni', 'Dep'),
            'hash',
            new DateTimeImmutable(),
            Uuid::uuid4()->toString()
        );
    }

    public static function createActiveUserByEmail(Email $email = null): User
    {
        $user = self::createInactiveUserByEmail($email);
        $user->confirmSignUp();

        return $user;
    }

    public static function createActiveUserByNetwork(): User
    {
        return User::signUpByNetwork(
            Id::next(),
            new DateTimeImmutable(),
            new Name('Kot', 'Snoopdog'),
            'vk',
            '4465561'
        );
    }
}
