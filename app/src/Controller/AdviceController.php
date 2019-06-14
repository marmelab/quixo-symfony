<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Manager\GameManager;
use App\Manager\AdvisorManager;

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
}
