<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Activate;

use App\Model\User\UserErrorConstants;
use App\Tests\Unit\Model\User\UserTestHelper;
use PHPUnit\Framework\TestCase;

class ActivateUserTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();

        $user->block();
        $user->activate();

        $this->assertTrue($user->isActive());
        $this->assertFalse($user->isBlocked());
        $this->assertFalse($user->isWait());
    }

    public function testAlreadyActive(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();

        $this->expectExceptionMessage(UserErrorConstants::USER_IS_ALREADY_ACTIVE);
        $user->activate();
    }
}
