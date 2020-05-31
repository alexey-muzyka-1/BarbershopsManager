<?php

declare(strict_types=1);

namespace App\Model\Barbershop\Entity\Barbershop;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class Adress
{
    /**
     * @var string
     * @ORM\Column(type="string", name="city")
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string", name="street")
     */
    private $street;

    /**
     * @var int
     * @ORM\Column(type="integer", name="house")
     */
    private $house;

    public function __construct(string $city, string $street, int $house)
    {
        Assert::notEmpty($city);
        Assert::notEmpty($street);
        Assert::notEmpty($house);

        $this->city = $city;
        $this->street = $street;
        $this->house = $house;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getHouse(): int
    {
        return $this->house;
    }

    public function setHouse(int $house): void
    {
        $this->house = $house;
    }

    public function changeAdress(string $city, string $street, int $house): void
    {
        Assert::notEmpty($city);
        Assert::notEmpty($street);
        Assert::notEmpty($house);

        $this->city = $city;
        $this->street = $street;
        $this->house = $house;
    }

    public function getFullAdress(): string
    {
        return $this->city.' '.$this->street.' '.$this->house;
    }
}
