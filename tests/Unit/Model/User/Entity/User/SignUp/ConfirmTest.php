<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\User;
use App\Model\User\UserErrorConstants;
use App\Tests\Unit\Model\User\UserTestHelper;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testUserConfirmation()
    {
        $user = $user = UserTestHelper::createInactiveUserByEmail();

        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->isActive());

        $user->confirmSignUp();

        $this->assertTrue($user->isActive());
        $this->assertFalse($user->isWait());
        $this->assertNull($user->getToken());
    }

    public function testUserAlreadyConfirmed(): void
    {
        $user = $user = UserTestHelper::createInactiveUserByEmail();

        $user->confirmSignUp();
        $this->expectExceptionMessage(UserErrorConstants::USER_ALREADY_CONFIRMED);
        $user->confirmSignUp();
    }
}
