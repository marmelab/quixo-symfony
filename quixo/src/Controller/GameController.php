<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Entity\Coords;
use App\Manager\GameManager;

class GameController extends Controller
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
        $gameId = $gameManager->createGame();

        return $this->redirectToRoute('game', ['id' => $gameId]);
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
                $request->request->get('x'),
                $request->request->get('y')
            );
            $game = $gameManager->playCube($game, $coordsSelected, GameManager::CROSS_TEAM);
        }

        $movables = $gameManager->getMovables($game, GameManager::CROSS_TEAM);

        return new Response($twig->render('game.html.twig', [
            'game' => $game,
            'movables' => $movables,
        ]));
    }
}
