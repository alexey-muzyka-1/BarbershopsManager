<?php

declare(strict_types=1);

namespace App\Twig\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SidebarMenu
{
    private $factory;
    private $auth;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $auth)
    {
        $this->factory = $factory;
        $this->auth = $auth;
    }

    public function build(): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav']);

        $menu->addChild('Dashboard', ['route' => 'home'])
            ->setExtra('icon', 'nav-icon icon-speedometer')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Barbeshops Info')->setAttribute('class', 'nav-title');

        $menu->addChild('Companies', ['route' => 'barbershop_info.companies'])
            ->setExtra('routes', [
                ['route' => 'barbershop_info.companies'],
                ['pattern' => '/^barbershop_info.companies\..+/'],
            ])
                ->setExtra('icon', 'nav-icon icon-briefcase')
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Barbershops', ['route' => 'barbershop_info.barbershops'])
            ->setExtra('routes', [
                ['route' => 'barbershop_info.barbershops'],
                ['pattern' => '/^barbershop_info.barbershops\..+/'],
            ])
            ->setExtra('icon', 'nav-icon icon-briefcase')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Control')->setAttribute('class', 'nav-title');

        if ($this->auth->isGranted('ROLE_MANAGE_USERS')) {
            $menu->addChild('Users', ['route' => 'users'])
                ->setExtra('icon', 'nav-icon icon-people')
                ->setExtra('routes', [
                    ['route' => 'users'],
                    ['pattern' => '/^users\..+/'],
                ])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }

        $menu->addChild('Profile', ['route' => 'profile'])
            ->setExtra('icon', 'nav-icon icon-user')
            ->setExtra('routes', [
                ['route' => 'profile'],
                ['pattern' => '/^profile\..+/'],
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
