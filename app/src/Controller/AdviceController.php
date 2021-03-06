<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Manager\GameManager;
use App\Manager\AdvisorManager;
use App\Domain\Coords;

class AdviceController extends AbstractController
{
    /**
     * Return an advice for the player
     *
     * @param  Request        $request
     * @param  GameManager    $gameManager
     * @param  AdvisorManager $advisorManager
     *
     * @return Response
     */
    public function getAdvice(Request $request, GameManager $gameManager, AdvisorManager $advisorManager): Response
    {
        $id = $request->attributes->getInt('id');
        $game = $gameManager->getGame($id);

        $advice = $advisorManager->getBestAdvice($game);
        return $this->json($advice);
    }

    /**
     * Return the worst advice for the player
     *
     * @param  Request        $request
     * @param  GameManager    $gameManager
     * @param  AdvisorManager $advisorManager
     *
     * @return Response
     */
    public function getWorstAdvice(Request $request, GameManager $gameManager, AdvisorManager $advisorManager): Response
    {
        $id = $request->attributes->getInt('id');
        $game = $gameManager->getGame($id);

        $advice = $advisorManager->getWorstAdvice($game);
        return $this->json($advice);
    }

    /**
     * Return the advice that make my opponent less good
     *
     * @param  Request        $request
     * @param  GameManager    $gameManager
     * @param  AdvisorManager $advisorManager
     *
     * @return Response
     */
    public function getAdviceThatMakeMyOpponentLose(Request $request, GameManager $gameManager, AdvisorManager $advisorManager): Response
    {
        $id = $request->attributes->getInt('id');
        $game = $gameManager->getGame($id);

        $advice = $advisorManager->getAdviceThatMakeMyOpponentLose($game);
        return $this->json($advice);
    }

    /**
     * Return a board view of the advice
     *
     * @param  Request     $request
     * @param  GameManager $gameManager
     *
     * @return Response
     */
    public function previewAdvice(Request $request, GameManager $gameManager): Response
    {
        $id = $request->attributes->getInt('id');
        $game = $gameManager->getGame($id);

        list($coordsStart, $coordsEnd) = $this->getCoordsFromRequest($request);

        $newBoard = $gameManager->moveCube($game, $coordsStart, $coordsEnd, $game->getCurrentPlayer());
        $game->setBoard($newBoard);

        return $this->render('board.html.twig', [
            'game' => $game
        ]);
    }

    /**
     * Return coords start & coords end from request
     *
     * @param  Request $request
     *
     * @return array
     */
    private function getCoordsFromRequest(Request $request): array
    {
        $xStart = $request->attributes->getInt('xStart');
        $yStart = $request->attributes->getInt('yStart');
        $xEnd = $request->attributes->getInt('xEnd');
        $yEnd = $request->attributes->getInt('yEnd');

        $coordsStart = new Coords($xStart, $yStart);
        $coordsEnd = new Coords($xEnd, $yEnd);
        return [$coordsStart, $coordsEnd];
    }
}
