<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\GameRepository;
use App\Domain\Coords;
use App\Domain\TeamSelection;
use App\Manager\GameManager;
use App\Manager\SessionManager;
use App\Manager\AdvisorManager;
use App\Form\TeamType;
use App\Form\CancelSelectionType;

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
    public function assignPlayer(Request $request, GameManager $gameManager, SessionManager $sessionManager): Response
    {
        $id = $request->attributes->getInt('id');
        $game = $gameManager->getGame($id);

        $availablesTeams = $gameManager->getAvailablesTeams($game);
        if (count($availablesTeams) < 1) {
            return $this->redirectToRoute('spectate', [ 'id' => $id ]);
        } elseif (count($availablesTeams) === 1) {
            $sessionManager->storePlayerTeam($game, array_pop($availablesTeams));
            return $this->redirectToRoute('game', ['id' => $id]);
        }

        $teamSelection = new TeamSelection();
        $form = $this->createForm(TeamType::class, $teamSelection);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $teamSelection = $form->getData();
            $sessionManager->storePlayerTeam($game, $teamSelection);
            return $this->redirectToRoute('game', ['id' => $id]);
        }

        return $this->render('choose-team.html.twig', [
            'game' => $game,
            'availablesTeams' => $availablesTeams,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Display the game for players
     *
     * @param  GameManager      $gameManager
     * @param  CookieManager    $cookieManager
     *
     * @return Response
     */
    public function game(Request $request, GameManager $gameManager, SessionManager $sessionManager): Response
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
        if ($turnDone && $winner === null) {
            $gameManager->switchPlayer($game);
        }

        $cancelForm = $this->createForm(CancelSelectionType::class, null, [
            'action' => $this->generateUrl('cancel-selection', ['id' => $game->getId()])
        ]);
        $displayCancelForm = $game->getSelectedCube() !== null && $game->getCurrentPlayer() === $playerTeam;

        $movables = $gameManager->getMovablesOrDestinationsForPlayer($game, $playerTeam);

        return $this->render('game.html.twig', [
            'game' => $game,
            'movables' => $movables,
            'winningCubes' => $winningCubes,
            'waitForPlayer' => $playerTeam !== $game->getCurrentPlayer() && $winner === null,
            'playerTeam' => $playerTeam,
            'cancelForm' => $displayCancelForm ? $cancelForm->createView() : null,
        ]);
    }

    /**
     * Display neutral game for spectators
     *
     * @param  Request          $request
     * @param  GameManager      $gameManager
     * @param  Twig_Environment $twig
     *
     * @return Response
     */
    public function spectate(Request $request, GameManager $gameManager): Response
    {
        $game = $gameManager->getGame($request->attributes->getInt('id'));
        list($winner, $winningCubes) = $gameManager->resolveWinnerAndWinningCubes($game);

        return $this->render('game.html.twig', [
            'game' => $game,
            'winningCubes' => $winningCubes,
            'waitForPlayer' => $winner === null,
            'playerTeam' => 0,
            'movables' => [],
        ]);
    }

    /**
     * Cancel the player selection
     *
     * @param  Request     $request
     * @param  GameManager $gameManager
     *
     * @return RedirectResponse
     */
    public function cancelSelection(Request $request, GameManager $gameManager): RedirectResponse
    {
        $id = $request->attributes->getInt('id');
        $game = $gameManager->getGame($id);

        $form = $this->createForm(CancelSelectionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gameManager->cancelSelection($game);
        }
        return $this->redirectToRoute('game', ['id' => $id]);
    }
}
