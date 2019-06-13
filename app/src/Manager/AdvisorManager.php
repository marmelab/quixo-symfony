<?php

namespace App\Manager;

use App\Entity\Game;
use Unirest\Request as Request;
use Unirest\Response as Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AdvisorManager
{

    private $adivsorUrl;

    public function __construct(ParameterBagInterface $params)
    {
        $this->adivsorUrl = $params->get('advisor_url');
    }

    private function post($body): Response
    {
        $headers = ['Accept' => 'application/json'];
        return Request::post($this->adivsorUrl.'/best-move', $headers, $body);
    }

    /**
     * Return the team of the player stored in the sessoin
     *
     * @param  Game $game
     *
     * @return int
     */
    public function getAdvice(Game $game)
    {
        $body = [
            'Grid' => $game->getBoard(),
            'Player' => $game->getCurrentPlayer(),
        ];
        $response = $this->post(json_encode($body));
        $body = $response->body;

        $advice = [
            'coordsStart' => [
                'x' => $body->CoordsStart->X,
                'y' => $body->CoordsStart->Y,
            ],
            'coordsEnd' => [
                'x' => $body->CoordsEnd->X,
                'y' => $body->CoordsEnd->Y,
            ],
        ];
        return $advice;
    }
}
