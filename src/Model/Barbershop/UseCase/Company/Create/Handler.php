<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Company\Create;

use App\Model\Barbershop\Constants\CompanyError;
use App\Model\Barbershop\Entity\Company\Company;
use App\Model\Barbershop\Entity\Company\CompanyRepository;
use App\Model\Barbershop\Entity\Company\Id;
use App\Model\Flusher;
use DomainException;

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
        if ($this->repo->hasByName($command->name)) {
            throw new DomainException(CompanyError::COMPANY_ALREADY_EXISTS);
        }


        $company = new Company(
            Id::next(),
            $command->name,
            $command->owner
        );

        $this->repo->add($company);
        $this->flusher->flush();
    }
}
