<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Utils\GameUtils;

class GameController extends Controller
{
    /**
     * Create a new board and display it
     *
     * @param Twig_Environment $twig
     * @param GameRepository   $gameRepository
     *
     * @return Response
     */
    public function newGame(GameRepository $gameRepository): Response
    {
        $board = GameUtils::getEmptyBoard();
        $game = new Game($board);
        $gameRepository->save($game);

        return $this->redirectToRoute('game', ['id' => $game->getId()]);
    }

    /**
     * Display the game
     *
     * @param  int   $id The id of the game
     * @param  mixed $twig
     * @param  mixed $gameRepository
     *
     * @return Response
     */
    public function game(int $id, \Twig_Environment $twig, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->findOneBy(['id' => $id]);
        $movables = GameUtils::getMovables($game->getBoard());

        return new Response($twig->render('game.html.twig', [ 'game' => $game, 'movables' => $movables ]));
    }
}
