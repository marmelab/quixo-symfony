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
     * @param Twig_Environment $twig
     * @param GameRepository   $gameRepository
     *
     * @return Response
     */
    public function newGame(GameRepository $gameRepository, GameManager $gameManager): Response
    {
        $board =$gameManager->getEmptyBoard();
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
    public function game(Request $request, int $id, \Twig_Environment $twig, GameRepository $gameRepository, GameManager $gameManager): Response
    {
        $game = $gameRepository->findOneBy(['id' => $id]);
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('move-cube', $submittedToken)) {
            $coordsSelected = new Coords(
                $request->request->get('x'),
                $request->request->get('y')
            );
            $board = $gameManager->moveCube($game->getBoard(), $coordsSelected, GameManager::CROSS_TEAM);
            $game->setBoard($board);
            $gameRepository->save($game);
        }

        $movables = $gameManager->getMovables($game->getBoard(), GameManager::CROSS_TEAM);

        return new Response($twig->render('game.html.twig', [
            'game' => $game,
            'movables' => $movables,
        ]));
    }
}
