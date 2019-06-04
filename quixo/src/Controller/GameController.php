<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Utils\GameUtils;

class GameController
{

    private $twig;

    private $gameRepository;

    public function __construct(\Twig_Environment $twig, GameRepository $gameRepository)
    {
        $this->twig = $twig;
        $this->gameRepository = $gameRepository;
    }

    public function index()
    {
        $board = GameUtils::getEmptyBoard();
        $game = new Game($board);
        $this->gameRepository->save($game);

        return new Response($this->twig->render('main.html.twig', [ 'board' => $board ]));
    }
}
