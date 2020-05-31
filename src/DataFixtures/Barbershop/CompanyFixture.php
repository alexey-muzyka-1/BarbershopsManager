<?php

declare(strict_types=1);

namespace App\DataFixtures\Barbershop;

use App\DataFixtures\FixtureConstant;
use App\DataFixtures\User\UserFixture;
use App\Model\Barbershop\Entity\Company\Company;
use App\Model\Barbershop\Entity\Company\Id;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixture extends Fixture implements DependentFixtureInterface
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createCompany1());
        $manager->persist($this->createCompany2());
        $manager->persist($this->createCompany3());
        $manager->persist($this->createCompany4());

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }

    private function createCompany1(): Company
    {
        $company = new Company(
            Id::next(),
            'ProBarber',
            $this->faker->firstName.' '.$this->faker->lastName
        );

        $this->addReference(FixtureConstant::COMPANY_1, $company);

        return $company;
    }

    private function createCompany2(): Company
    {
        $company = new Company(
            Id::next(),
            'GOGO Barber',
            $this->faker->firstName.' '.$this->faker->lastName
        );

        $this->addReference(FixtureConstant::COMPANY_2, $company);

        return $company;
    }

    private function createCompany3(): Company
    {
        $company = new Company(
            Id::next(),
            'Barbershop N1',
            $this->faker->firstName.' '.$this->faker->lastName
        );

        $this->addReference(FixtureConstant::COMPANY_3, $company);

        return $company;
    }

    private function createCompany4(): Company
    {
        $company = new Company(
            Id::next(),
            'BarberPro',
            $this->faker->firstName.' '.$this->faker->lastName
        );

        $this->addReference(FixtureConstant::COMPANY_4, $company);

        return $company;
    }
}
