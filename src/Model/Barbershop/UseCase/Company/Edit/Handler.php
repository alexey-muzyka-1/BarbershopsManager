<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Company\Edit;

use App\Model\Barbershop\Entity\Company\Company;
use App\Model\Barbershop\Entity\Company\CompanyRepository;
use App\Model\Barbershop\Entity\Company\Id;
use App\Model\Flusher;

class Handler
{
    private $repo;
    private $flusher;

    public function __construct(CompanyRepository $repo, Flusher $flusher)
    {
        $this->repo = $repo;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $company = $this->repo->get(new Id($command->id));
        $company->setName($command->name);
        $company->setOwner($command->owner);

        $this->flusher->flush();
    }
}
