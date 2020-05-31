<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Barbershop\Move;

use App\Model\Barbershop\Entity\Barbershop\Barbershop;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $id;
    /**
     * @Assert\NotBlank()
     */
    public $company;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromMember(Barbershop $barbershop): self
    {
        $command = new self($barbershop->getId()->getValue());
        $command->company = $barbershop->getCompany()->getId()->getValue();
        return $command;
    }
}