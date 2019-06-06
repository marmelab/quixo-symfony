<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GameRepository;
use App\Entity\Coords;
use App\Manager\GameManager;

class GameController extends AbstractController
{
    /**
     * Create a new board and display it
     *
     * @param GameRepository   $gameRepository
     *
     * @return Response
     */
    public function newGame(GameManager $gameManager): Response
    {
        $game = $gameManager->createGame();

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
    public function game(Request $request, int $id, \Twig_Environment $twig, GameManager $gameManager): Response
    {
        $game = $gameManager->getGame($id);
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('move-cube', $submittedToken)) {
            $coordsSelected = new Coords(
                $request->request->getInt('x'),
                $request->request->getInt('y')
            );
            if ($game->getSelectedCube() === null) {
                $game = $gameManager->selectCube($game, $coordsSelected);
            } else {
                $game = $gameManager->playCube($game, $coordsSelected, GameManager::CROSS_TEAM);
            }
        }

        list($winner, $winningCubes) = $gameManager->resolveWinnerAndWinningCubes($game);
        if ($winner !== $game->getWinner()) {
            $gameManager->persistWinner($game, $winner);
        }

        $movables = $game->getWinner() === null
            ? $gameManager->getMovablesOrDestinations($game)
            : [];

            return new Response($twig->render('game.html.twig', [
            'game' => $game,
            'movables' => $movables,
            'winningCubes' => $winningCubes,
        ]));
    }
}
