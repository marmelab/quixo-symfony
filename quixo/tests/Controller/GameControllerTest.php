<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Utils\GameUtils;

class GameControllerTest extends WebTestCase
{
    /**
     * Test that new game page return a 200 response and an empty board
     *
     * @return void
     */
    public function testNewGame(): void
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
