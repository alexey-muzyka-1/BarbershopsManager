<?php

namespace App\DataFixtures\Barbershop;

use App\DataFixtures\FixtureConstant;
use App\Model\Barbershop\Entity\Barbershop\Adress;
use App\Model\Barbershop\Entity\Barbershop\Barbershop;
use App\Model\Barbershop\Entity\Barbershop\Id;
use App\Model\Barbershop\Entity\Company\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BarbershopFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < FixtureConstant::FAKE_BARBERSHOPS_AMOUNT; ++$i) {
            $manager->persist($this->createBarbershop());
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CompanyFixture::class,
        ];
    }

    private function createBarbershop(): Barbershop
    {
        $barbershop = new Barbershop(
          Id::next(),
          $this->faker->company,
          $this->createAdress(),
          $this->getRandomCompany()
        );

        if ($this->faker->boolean(30)) {
            $barbershop->setAbout($this->faker->text(100));
        }

        if ($this->faker->boolean(30)) {
            $barbershop->archive();
        }

        return $barbershop;
    }

    private function createAdress(): Adress
    {
        return new Adress(
            $this->faker->city,
            $this->faker->streetName,
            $this->faker->randomDigitNotNull
        );
    }

    private function getRandomCompany(): Company
    {
        /** @var Company $company */
        $company = $this->getReference(FixtureConstant::getRundomCompanyReference());

        return $company;
    }
}
