<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Game;

class GameControllerTest extends WebTestCase
{
    /**
     * Test that new game page return a redirect response
     *
     * @return void
     */
    public function testNewGame(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/new-game');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Test that game page return a board of the correct size
     *
     * @return void
     */
    public function testGame(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/game/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $game = new Game();

        $this->assertEquals(
            $game->getCols() * $game->getRows(),
            $crawler->filter('.cube')->count()
        );
    }
}
