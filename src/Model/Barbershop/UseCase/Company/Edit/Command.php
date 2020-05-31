<?php

namespace App\Model\Barbershop\UseCase\Company\Edit;

use App\Model\Barbershop\Entity\Company\Company;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
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
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     */
    public $owner;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromCompany(Company $company)
    {
        $command = new self($company->getId()->getValue());
        $command->name = $company->getName();

        return $command;
    }
}
