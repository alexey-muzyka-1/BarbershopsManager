<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Company\Create;

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
     */
    public $owner;
}