<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use DateTimeImmutable;
use Monolog\Test\TestCase;
use Ramsey\Uuid\Uuid;

class RequestTest extends TestCase
{
    public function testUserCreated(): void
    {
        $user = User::signUpByEmail(
            $id = Id::next(),
            $email = new Email('test@gmail.com'),
            new Name($firstName = 'Joni', $lastName = 'Dep'),
            $passwordHash = 'hash',
            $createdAt = new DateTimeImmutable(),
            $token = Uuid::uuid4()->toString()
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->isActive());

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($firstName, $user->getName()->getFirstName());
        $this->assertEquals($lastName, $user->getName()->getLastName());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($passwordHash, $user->getPasswordHash());
        $this->assertEquals($createdAt, $user->getCreatedAt());
        $this->assertEquals($token, $user->getToken());

        $this->assertEquals($user->getRole(), Role::user());
    }
}
