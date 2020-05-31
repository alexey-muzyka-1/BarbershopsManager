<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Email;

use App\Model\User\Entity\User\Email;
use App\Model\User\UserErrorConstants;
use App\Tests\Unit\Model\User\UserTestHelper;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();

        $user->requestEmailChanging(
            $email = new Email('new@app.test'),
            $token = 'token'
        );

        self::assertEquals($email, $user->getNewEmail());
        self::assertEquals($token, $user->getNewEmailToken());
    }

    public function testSameEmails(): void
    {
        $user = UserTestHelper::createActiveUserByEmail($email = new Email('test@email.com'));

        $this->expectExceptionMessage(UserErrorConstants::USER_INCORRECT_EMAIL);
        $user->requestEmailChanging($email, 'token');
    }

    public function testNotConfirmedUser(): void
    {
        $user = UserTestHelper::createInactiveUserByEmail();

        $this->expectExceptionMessage(UserErrorConstants::USER_IS_NOT_ACTIVE);
        $user->requestEmailChanging(new Email('new@app.test'), 'token');
    }
}
