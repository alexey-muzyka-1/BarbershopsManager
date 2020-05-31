<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Barbershop\Edit;

use App\Model\Barbershop\Entity\Barbershop\Adress;
use App\Model\Barbershop\Entity\Barbershop\BarbershopRepository;
use App\Model\Barbershop\Entity\Barbershop\Id;
use App\Model\Barbershop\Entity\Company\CompanyRepository;
use App\Model\Barbershop\Entity\Company\Id as GroupId;
use App\Model\Flusher;

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
        $barbershop = $this->repo->get(new Id($command->id));
        $company = $this->companies->get(new GroupId($command->company));

        $barbershop->edit(
            $command->name,
            new Adress(
                $command->city,
                $command->street,
                $command->house
            ),
            $company,
            $command->about
        );

        $this->flusher->flush();
    }
}
