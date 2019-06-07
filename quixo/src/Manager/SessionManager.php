<?php

namespace App\Manager;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionManager
{
    private const PREFIX_GAME = 'quixo-';

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

    /**
     * Return the team of the player stored in the sessoin
     *
     * @param  Game $game
     *
     * @return int
     */
    public function getPlayerTeam(Game $game): int
    {
        return (int)$this->session->get(self::PREFIX_GAME . $game->getId(), 0);
    }

    /**
     * Set the team of the player for the game id in the session and update Game
     *
     * @param  Game $game
     * @param  int  $team
     *
     * @return void
     */
    public function storePlayerTeam(Game $game, int $team): void
    {
        $this->session->set(self::PREFIX_GAME . $game->getId(), strval($team));
        $game->setNumberOfPlayers($game->getNumberOfPlayers() + 1);
        $this->gameRepository->save($game);
    }
}
