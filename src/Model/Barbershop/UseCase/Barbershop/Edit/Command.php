<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Barbershop\Edit;

use App\Model\Barbershop\Entity\Barbershop\Barbershop;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     */
    public $name;

    /**
     * @var string
     * @Assert\Length(min="3")
     */
    public $about;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     */
    public $city;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     */
    public $street;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $house;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $company;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromBarbershop(Barbershop $barbershop)
    {
        $command = new self($barbershop->getId()->getValue());
        $command->name = $barbershop->getName();
        $command->city = $barbershop->getAdress()->getCity();
        $command->street = $barbershop->getAdress()->getStreet();
        $command->house = $barbershop->getAdress()->getHouse();

        return $command;
    }
}