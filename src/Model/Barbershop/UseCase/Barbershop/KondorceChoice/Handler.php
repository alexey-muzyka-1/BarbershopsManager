<?php

declare(strict_types=1);

namespace App\Model\Barbershop\UseCase\Barbershop\KondorceChoice;

use App\Model\Barbershop\Entity\KondorceChoice\KondorceChoice;

class Handler
{
    private $kondorceChoice;

    public function __construct()
    {
        $this->kondorceChoice = new KondorceChoice();
    }

    public function handle(Command $command): void
    {
        $bestValue = $this->kondorceChoice->findBestVariant($command->values);
    }
}
