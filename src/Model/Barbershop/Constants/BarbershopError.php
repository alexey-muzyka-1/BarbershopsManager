<?php

declare(strict_types=1);

namespace App\Model\Barbershop\Constants;

class BarbershopError
{
    public const BARBERSHOP_ALREADY_EXISTS = 'Barbershop with this name already exists!';
    public const BARBERSHOP_DOSENT_EXISTS = 'Such barbershop dosen\'t exists!';

    public const BARBERSHOP_ALREADY_ACTIVE = 'Barbershop already active!';
    public const BARBERSHOP_ALREADY_ARCHIVED = 'Barbershop already archived!';
}