<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Game;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use App\Manager\SessionManager;
use App\Manager\GameManager;

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
        $client->request('GET', '/new-game');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }


    /**
     * Test that game page return a board of the correct size when the session is set
     *
     * @return void
     */
    public function testAssignPlayerRedirect(): void
    {
        $client = static::createClient();
        $client->request('GET', '/assign-player/1');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }


    /**
     * Test that game page return a board of the correct size when the session is set
     *
     * @return void
     */
    public function testGameWhenIamAPlayer(): void
    {
        $session = new Session(new MockFileSessionStorage());
        $session->set(SessionManager::PREFIX_GAME.'1', GameManager::CROSS_TEAM);

        $client = static::createClient();
        $client->getContainer()->set('session', $session);

        $crawler = $client->request('GET', '/game/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $game = new Game();

        $this->assertEquals(
            $game->getCols() * $game->getRows(),
            $crawler->filter('.cube')->count()
        );
    }

    /**
     * Test that game page redirect when i'm not a player
     *
     * @return void
     */
    public function testGameWhenIamNotAPlayer(): void
    {

        $client = static::createClient();
        $client->request('GET', '/game/1');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
