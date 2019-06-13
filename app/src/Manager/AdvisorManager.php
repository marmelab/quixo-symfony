<?php

namespace App\Manager;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Unirest\Request as Request;

class AdvisorManager
{
    private const ADVISOR_URL = 'http://advisor:8001';

    private $gameRepository;
    private $session;

    /**
     * __construct
     *
     * @param  GameRepository  $gameRepository
     * @param  SessionInterface $session
     *
     * @return void
     */
    public function __construct(GameRepository $gameRepository, SessionInterface $session)
    {
        $this->gameRepository = $gameRepository;
        $this->session = $session;
    }

    private function post($body)
    {
        $headers = ['Accept' => 'application/json'];
        return Request::post(self::ADVISOR_URL.'/best-move', $headers, $body);
    }

    /**
     * Return the team of the player stored in the sessoin
     *
     * @param  Game $game
     *
     * @return int
     */
    public function getAdvice(Game $game)
    {
        $body = [
            'Grid' => $game->getBoard(),
            'Player' => $game->getCurrentPlayer(),
        ];
        $response = $this->post(json_encode($body));
        dump($response);
    }
}
