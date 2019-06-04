<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Utils\GameUtils;

class MainControllerTest extends WebTestCase
{
    public function testNewGame()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            GameUtils::N_COLS * GameUtils::N_ROWS,
            $crawler->filter('.cube')->count()
        );
    }
}
