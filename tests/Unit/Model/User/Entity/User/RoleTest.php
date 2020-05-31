<?php

declare(strict_types=1);

namespace Unit\Model\User\Entity\User;

use App\Model\User\Entity\User\Role;
use App\Model\User\UserErrorConstants;
use App\Tests\Unit\Model\User\UserTestHelper;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testRoleAdmin(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();

        $user->changeRole(Role::admin());

        $this->assertFalse($user->getRole()->isUser());
        $this->assertTrue($user->getRole()->isAdmin());
    }

    public function testUndefinedRole(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();

        $this->expectExceptionMessage(UserErrorConstants::USER_UNDEFINED_ROLE);
        $user->changeRole(new Role('ROLE_TEST'));
    }

    public function testEqualsRoles(): void
    {
        $user = UserTestHelper::createActiveUserByEmail();

        $this->expectExceptionMessage(UserErrorConstants::USER_EQUALS_ROLES);
        $user->changeRole(Role::user());
    }
}
