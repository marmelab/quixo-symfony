<?php

namespace App\Manager;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class SessionManager
{
    public const PREFIX_GAME = 'quixo-';

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
        if ($game->getPlayer1() === null) {
            $game->setPlayer1($team);
        } elseif ($game->getPlayer2() === null) {
            $game->setPlayer2($team);
        }
        $this->gameRepository->save($game);
    }
}
