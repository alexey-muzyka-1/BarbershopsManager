<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Reset;

use App\Model\User\Entity\User\ResetToken;
use App\Model\User\UserErrorConstants;
use App\Tests\Unit\Model\User\UserTestHelper;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();
        $user->requestPasswordReset(...$this->getValidTokenAndTime());

        self::assertNotNull($user->getResetToken());
    }

    public function testAlreadyRequested(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();
        $user->requestPasswordReset(...$this->getValidTokenAndTime());

        $this->expectExceptionMessage(UserErrorConstants::USER_RESET_IS_REQUESTED);
        $user->requestPasswordReset(...$this->getValidTokenAndTime());
    }

    public function testTokenExpired(): void
    {
        $now = new DateTimeImmutable();

        $user = UserTestHelper::createActiveUserByEmail();

        $token1 = new ResetToken('token', $now->modify('+1 day'));
        $user->requestPasswordReset($token1, $now);

        self::assertEquals($token1, $user->getResetToken());

        $token2 = new ResetToken('token', $now->modify('+3 day'));
        $user->requestPasswordReset($token2, $now->modify('+2 day'));

        self::assertEquals($token2, $user->getResetToken());
    }

    public function testUserNotConfirmed(): void
    {
        $user = UserTestHelper::createInactiveUserByEmail();

        $this->expectExceptionMessage(UserErrorConstants::USER_IS_NOT_ACTIVE);
        $user->requestPasswordReset(...$this->getValidTokenAndTime());
    }

    public function testWithoutEmail(): void
    {
        $user = UserTestHelper::createActiveUserByNetwork();

        $this->expectExceptionMessage(UserErrorConstants::USER_EMAIL_IS_NOT_SPECIFIED);
        $user->requestPasswordReset(...$this->getValidTokenAndTime());
    }

    private function getValidTokenAndTime(): array
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        return [$token, $now];
    }
}
