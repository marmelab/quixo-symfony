<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Utils\GameUtils;

class GameController
{
    /**
     * newGame
     *
     * @param Twig_Environment $twig
     * @param GameRepository   $gameRepository
     *
     * @return Response
     */
    public function newGame(\Twig_Environment $twig, GameRepository $gameRepository): Response
    {
        $board = GameUtils::getEmptyBoard();
        $game = new Game($board);
        $gameRepository->save($game);

        return new Response($twig->render('main.html.twig', [ 'game' => $game ]));
    }
}
