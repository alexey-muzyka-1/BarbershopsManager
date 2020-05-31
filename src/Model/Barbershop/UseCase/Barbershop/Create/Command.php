<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Barbershop\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     */
    public $name;

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
}
