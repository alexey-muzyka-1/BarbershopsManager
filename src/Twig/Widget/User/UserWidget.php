<?php

declare(strict_types=1);

namespace App\Twig\Widget\User;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserWidget extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_role', [$this, 'role'], ['needs_environment' => true, 'is_safe' => ['html']]),
            new TwigFunction('user_status', [$this, 'status'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function role(Environment $twig, string $role): string
    {
        return $twig->render('widget/user/role.html.twig', [
            'role' => $role,
        ]);
    }

    public function status(Environment $twig, string $status): string
    {
        return $twig->render('widget/user/status.html.twig', [
            'status' => $status,
        ]);
    }
}
