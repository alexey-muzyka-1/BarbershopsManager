<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Barbershop\Create;

use App\Model\Barbershop\Constants\BarbershopError;
use App\Model\Barbershop\Entity\Barbershop\Adress;
use App\Model\Barbershop\Entity\Barbershop\Barbershop;
use App\Model\Barbershop\Entity\Barbershop\BarbershopRepository;
use App\Model\Barbershop\Entity\Barbershop\Id;
use App\Model\Barbershop\Entity\Company\CompanyRepository;
use App\Model\Barbershop\Entity\Company\Id as GroupId;
use App\Model\Flusher;
use DomainException;

class Handler
{
    private $repo;
    private $companies;
    private $flusher;

    public function __construct(BarbershopRepository $repo, CompanyRepository $companies, Flusher $flusher)
    {
        $this->repo = $repo;
        $this->companies = $companies;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if ($this->repo->hasByName($command->name)) {
            throw new DomainException(BarbershopError::BARBERSHOP_ALREADY_EXISTS);
        }

        $company = $this->companies->get(new GroupId($command->company));

        $barbershop = new Barbershop(
            Id::next(),
            $command->name,
            new Adress(
                $command->city,
                $command->street,
                $command->house
            ),
            $company
        );

        $this->repo->add($barbershop);
        $this->flusher->flush();
    }
}
