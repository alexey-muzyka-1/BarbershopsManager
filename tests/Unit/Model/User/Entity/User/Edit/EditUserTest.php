<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Edit;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Name;
use App\Tests\Unit\Model\User\UserTestHelper;
use PHPUnit\Framework\TestCase;

class EditUserTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = UserTestHelper::createActiveUserByEmail($oldEmail = new Email('old@email.com'));

        $this->assertEquals($oldEmail, $user->getEmail());

        $user->edit(
            $email = new Email('new@mail.com'),
            $name = new Name('Adom', 'Smith')
        );

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($name, $user->getName());
    }
}
