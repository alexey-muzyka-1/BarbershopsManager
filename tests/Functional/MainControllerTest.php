<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testGuestRedirect(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $client->getResponse()->headers->get('Location'));
    }

    /**
     * @dataProvider provideUrls
     */
    public function testGuestAccess(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function provideUrls()
    {
        return [
            ['/login'],
            ['/signup'],
            ['/reset'],
        ];
    }
}
