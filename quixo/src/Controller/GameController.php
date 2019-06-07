<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\GameRepository;
use App\Entity\Coords;
use App\Manager\GameManager;
use App\Manager\SessionManager;

class GameController extends AbstractController
{
    /**
     * Create a new board and display it
     *
     * @param GameRepository   $gameRepository
     *
     * @return RedirectResponse
     */
    public function newGame(GameManager $gameManager): RedirectResponse
    {
        $game = $gameManager->createGame();

        return $this->redirectToRoute('assign-player', ['id' => $game->getId()]);
    }

    /**
     * Assign a team to a new player. If game already has 2 players, redirect to a new game
     *
     * @param  Request        $request
     * @param  GameManager    $gameManager
     * @param  SessionManager $sessionManager
     *
     * @return RedirectResponse
     */
    public function assignPlayer(Request $request, GameManager $gameManager, SessionManager $sessionManager): RedirectResponse
    {
        $id = $request->attributes->getInt('id');
        $game = $gameManager->getGame($id);
        $numberOfPlayers = $game->getNumberOfPlayers();
        if ($numberOfPlayers === 0) {
            $sessionManager->storePlayerTeam($game, GameManager::CROSS_TEAM);
        } elseif ($numberOfPlayers === 1) {
            $sessionManager->storePlayerTeam($game, GameManager::CIRCLE_TEAM);
        } else {
            return $this->redirectToRoute('newgame', ['id' => $id]);
        }
        return $this->redirectToRoute('game', ['id' => $id]);
    }

    /**
     * Display the game for players
     *
     * @param  Twig_Environment $twig
     * @param  GameManager      $gameManager
     * @param  CookieManager    $cookieManager
     *
     * @return Response
     */
    public function game(Request $request, \Twig_Environment $twig, GameManager $gameManager, SessionManager $sessionManager): Response
    {
        $game = $gameManager->getGame($request->attributes->getInt('id'));
        $playerTeam = $sessionManager->getPlayerTeam($game);

        if ($playerTeam !== GameManager::CIRCLE_TEAM && $playerTeam !== $gameManager::CROSS_TEAM) {
            return $this->redirectToRoute('assign-player', ['id' => $game->getId()]);
        }

        $turnDone = false;

        if ($this->isCsrfTokenValid('move-cube', $request->request->get('token'))) {
            $coords = new Coords($request->request->getInt('x'), $request->request->getInt('y'));
            $turnDone = $gameManager->playPlayerTurn($coords, $game, $playerTeam);
        }

        list($winner, $winningCubes) = $gameManager->resolveWinnerAndWinningCubes($game);
        if ($winner !== $game->getWinner()) {
            $gameManager->persistWinner($game, $winner);
        }
        if ($turnDone) {
            $gameManager->switchPlayer($game);
        }

        $movables = $gameManager->getMovablesOrDestinationsForPlayer($game, $playerTeam);

        return new Response($twig->render('game.html.twig', [
            'game' => $game,
            'movables' => $movables,
            'winningCubes' => $winningCubes,
            'waitForPlayer' => $playerTeam !== $game->getCurrentPlayer() && $winner === null,
            'playerTeam' => $playerTeam,
        ]));
    }
}
