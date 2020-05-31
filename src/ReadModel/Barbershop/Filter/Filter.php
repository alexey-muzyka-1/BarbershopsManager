<?php

declare(strict_types=1);

namespace App\ReadModel\Barbershop\Filter;

use App\Model\Barbershop\Entity\Barbershop\Barbershop;

class Filter
{
    public $name;
    public $adress;
    public $companyName;
    public $status = Barbershop::ACTIVE;
    public $perPage = 10;
}