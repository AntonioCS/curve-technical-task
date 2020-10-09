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
}
