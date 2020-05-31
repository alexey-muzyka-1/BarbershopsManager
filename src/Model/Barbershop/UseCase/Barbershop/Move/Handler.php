<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Barbershop\Move;

use App\Model\Barbershop\Entity\Barbershop\BarbershopRepository;
use App\Model\Barbershop\Entity\Barbershop\Id;
use App\Model\Barbershop\Entity\Company\CompanyRepository;
use App\Model\Barbershop\Entity\Company\Id as CompanyId;
use App\Model\Flusher;

class Handler
{
    private $barbershops;
    private $companies;
    private $flusher;

    public function __construct(BarbershopRepository $barbershops, CompanyRepository $companies, Flusher $flusher)
    {
        $this->barbershops = $barbershops;
        $this->companies = $companies;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $barbershop = $this->barbershops->get(new Id($command->id));
        $company = $this->companies->get(new CompanyId($command->company));

        $barbershop->changeCompany($company);

        $this->flusher->flush();
    }
}