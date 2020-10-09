<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{

    public function testIndex() : void
    {
        $client = static::createClient();

        $client->request('GET', '/api/rate');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIndexHasContent() : void
    {
        $client = static::createClient();

        $client->request('GET', '/api/rate');
        $content = $client->getResponse()->getContent();
        $data = \json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('Currency-Values', $data);
        self::assertArrayHasKey('Exchange', $data);
    }
}
