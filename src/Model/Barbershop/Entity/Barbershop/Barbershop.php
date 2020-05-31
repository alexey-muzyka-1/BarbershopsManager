<?php

declare(strict_types=1);

namespace App\Model\Barbershop\Entity\Barbershop;

use App\Model\Barbershop\Constants\BarbershopError;
use App\Model\Barbershop\Entity\Company\Company;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity
 * @ORM\Table(name="barbershop_barbershop")
 */
class Barbershop
{
    public const ACTIVE = 'active';
    public const ARCHIVED = 'archived';

    /**
     * @var Id
     * @ORM\Id
     * @ORM\Column(type="barbershop_id")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="name")
     */
    private $name;

    /**
     * @var Adress
     * @ORM\Embedded(class="Adress", columnPrefix="adress_")
     */
    private $adress;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Model\Barbershop\Entity\Company\Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $company;

    /**
     * @var string
     * @ORM\Column(type="text", name="about", nullable=true)
     */
    private $about;

    /**
     * @var string
     * @ORM\Column(type="string", name="status")
     */
    private $status = self::ACTIVE;

    public function __construct(Id $id, string $name, Adress $adress, Company $company)
    {
        $this->id = $id;
        $this->name = $name;
        $this->adress = $adress;
        $this->company = $company;
    }

    public function edit(string $name, Adress $adress, Company $company, ?string $about): void
    {
        $this->name = $name;
        $this->adress = $adress;
        $this->company = $company;
        $this->about = $about;
    }

    public function changeCompany(Company $company): void
    {
        $this->company = $company;
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

    public function getAdress(): Adress
    {
        return $this->adress;
    }

    public function setAdress(Adress $adress): void
    {
        $this->adress = $adress;
    }

    public function getFullAdress(): string
    {
        return $this->adress->getFullAdress();
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(string $about): void
    {
        $this->about = $about;
    }

    public function isActive(): bool
    {
        return self::ACTIVE === $this->status;
    }

    public function isArchived(): bool
    {
        return self::ARCHIVED === $this->status;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException(BarbershopError::BARBERSHOP_ALREADY_ACTIVE);
        }
        $this->status = self::ACTIVE;
    }

    public function archive(): void
    {
        if ($this->isArchived()) {
            throw new DomainException(BarbershopError::BARBERSHOP_ALREADY_ARCHIVED);
        }
        $this->status = self::ARCHIVED;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
