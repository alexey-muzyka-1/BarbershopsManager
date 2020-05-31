<?php

declare(strict_types=1);

namespace App\Model\Barbershop\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="barbershop_company")
 */
class Company
{
    /**
     * @var Id
     * @ORM\Id
     * @ORM\Column(type="barbershop_company_id")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="name")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", name="owner")
     */
    private $owner;

    public function __construct(Id $id, string $name, $owner)
    {
        $this->id = $id;
        $this->name = $name;
        $this->owner = $owner;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

}
