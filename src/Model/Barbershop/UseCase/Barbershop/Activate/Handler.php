<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Barbershop\Activate;

use App\Model\Barbershop\Entity\Barbershop\BarbershopRepository;
use App\Model\Barbershop\Entity\Barbershop\Id;
use App\Model\Flusher;

class Handler
{
    private $repo;
    private $flusher;

    public function __construct(BarbershopRepository $repo, Flusher $flusher)
    {
        $this->repo = $repo;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $barbershop = $this->repo->get(new Id($command->id));
        $barbershop->activate();

        $this->flusher->flush();
    }
}
