<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexController extends WebTestCase
{
    /**
     * Test that new game page return a 200 response and an empty board
     *
     * @return void
     */
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('a:contains("New game")')->count());
    }
}
