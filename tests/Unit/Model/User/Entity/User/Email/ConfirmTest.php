<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Email;

use App\Model\User\Entity\User\Email;
use App\Model\User\UserErrorConstants;
use App\Tests\Unit\Model\User\UserTestHelper;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();
        $user->requestEmailChanging(
            $email = new Email('new@app.test'),
            $token = 'token'
        );
        $user->confirmEmailChanging($token);

        self::assertEquals($email, $user->getEmail());
        self::assertNull($user->getNewEmailToken());
        self::assertNull($user->getNewEmail());
    }

    public function testIncorectToken(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();
        $user->requestEmailChanging(
            new Email('new@app.test'),
            'token'
        );

        $this->expectExceptionMessage(UserErrorConstants::USER_INCORRECT_TOKEN);
        $user->confirmEmailChanging('annother token');
    }

    public function testNotRequested(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();

        $this->expectExceptionMessage(UserErrorConstants:: USER_CHANGING_IS_NOT_REQUESTED);
        $user->confirmEmailChanging('token');
    }
}
