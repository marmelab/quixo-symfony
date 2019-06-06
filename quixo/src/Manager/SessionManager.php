<?php

namespace App\Manager;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionManager
{
    public const PREFIX_GAME = 'quixo-';

    private $gameRepository;
    private $session;

    public function __construct(GameRepository $gameRepository, SessionInterface $session)
    {
        $this->gameRepository = $gameRepository;
        $this->session = $session;
    }

    public function getPlayerTeam(Game $game)
    {
        return (int)$this->session->get(self::PREFIX_GAME . $game->getId(), 0);
    }

    public function storePlayerTeam(Game $game, int $team)
    {
        $this->session->set(self::PREFIX_GAME . $game->getId(), strval($team));
        $game->setNumberOfPlayers($game->getNumberOfPlayers() + 1);
        $this->gameRepository->save($game);
    }
}
