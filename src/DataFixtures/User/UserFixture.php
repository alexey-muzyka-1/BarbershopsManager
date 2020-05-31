<?php

namespace App\DataFixtures\User;

use App\DataFixtures\FixtureConstant;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

class UserFixture extends Fixture
{
    private $defaultPassword;
    private $faker;

    public function __construct(PasswordHasher $hasher)
    {
        $this->defaultPassword = $hasher->hash('111111');
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createAdmin());
        $manager->persist($this->createUserByNetwork());

        $activeUsersNum = 0;

        while ($activeUsersNum <= FixtureConstant::FAKE_ACTIVE_USERS_AMOUNT) {
            $manager->persist($user = $this->createUser());

            if ($user->isActive()) {
                $this->addReference(FixtureConstant::USER_BY_FAKER.$activeUsersNum++, $user);
            }
        }

        $manager->flush();
    }

    private function createAdmin(): User
    {
        $user = User::signUpByEmail(
            Id::next(),
            new Email('admin@gmail.com'),
            new Name('I\'m the', 'Boss'),
            $this->defaultPassword,
            new DateTimeImmutable('now'),
            Uuid::uuid4()->toString()
        );
        $user->confirmSignUp();
        $user->changeRole(Role::admin());

        $this->addReference(FixtureConstant::USER_ADMIN, $user);

        return $user;
    }

    private function createUserByNetwork(): User
    {
        return User::signUpByNetwork(
        Id::next(),
        new DateTimeImmutable('-1 day'),
        new Name('Joni', 'Dep'),
        'vk',
        '4465561'
        );
    }

    private function createUser(): User
    {
        $user = User::signUpByEmail(
            Id::next(),
            new Email($this->faker->email),
            new Name($this->faker->firstName, $this->faker->lastName),
            $this->defaultPassword,
            DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween('-50 days', 'now')
            ),
            Uuid::uuid4()->toString()
        );

        if ($this->faker->boolean(75)) {
            $user->confirmSignUp();
        } else {
            $this->faker->boolean(50) ?: $user->block();
        }

        return $user;
    }
}
