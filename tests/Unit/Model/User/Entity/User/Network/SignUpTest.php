<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Network;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\Network;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SignUpTest extends TestCase
{
    public function testSignUpUser()
    {
        $user = User::signUpByNetwork(
            $id = Id::next(),
            $date = new DateTimeImmutable(),
            new Name('Kot', 'Snoopdog'),
            $network = 'vk',
            $identity = '4465561'
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->isActive());

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getCreatedAt());

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertInstanceOf(Network::class, $first = reset($networks));
        self::assertEquals($network, $first->getNetwork());
        self::assertEquals($identity, $first->getIdentity());

        $this->assertEquals($user->getRole(), Role::user());
    }
}
