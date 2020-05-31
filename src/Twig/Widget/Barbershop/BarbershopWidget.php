<?php

declare(strict_types=1);

namespace App\Twig\Widget\Barbershop;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BarbershopWidget extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('barbershop_status', [$this, 'status'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function status(Environment $twig, string $status): string
    {
        return $twig->render('widget/barbershop/barbershop/status.html.twig', [
            'status' => $status,
        ]);
    }
}
