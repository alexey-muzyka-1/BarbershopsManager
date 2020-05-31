<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Reset;

use App\Model\User\Entity\User\ResetToken;
use App\Model\User\UserErrorConstants;
use App\Tests\Unit\Model\User\UserTestHelper;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = $user = UserTestHelper::createActiveUserByEmail();

        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        $this->assertNotNull($user->getResetToken());

        $user->passwordReset($now, $hash = 'hash');

        $this->assertNull($user->getResetToken());
        $this->assertEquals($hash, $user->getPasswordHash());
    }

    public function testExpiredToken(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();

        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now);

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage(UserErrorConstants::USER_TOKEN_IS_EXPIRED);
        $user->passwordReset($now->modify('+1 day'), 'hash');
    }

    public function testNotRequested(): void
    {
        $user = $user = UserTestHelper::createActiveUserByEmail();

        $now = new DateTimeImmutable();

        $this->expectExceptionMessage(UserErrorConstants::USER_RESET_IS_NOT_REQUESTED);
        $user->passwordReset($now, 'hash');
    }

}
