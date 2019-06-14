<?php

namespace App\Manager;

use App\Entity\Game;
use App\Manager\GameManager;
use Unirest\Request as Request;
use Unirest\Response as Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AdvisorManager
{

    private $adivsorUrl;
    private $gameManager;

    public function __construct(ParameterBagInterface $params, GameManager $gameManager)
    {
        $this->adivsorUrl = $params->get('advisor_url');
        $this->gameManager = $gameManager;
    }

    private function post($body, $endpoint): Response
    {
        $headers = ['Accept' => 'application/json'];
        return Request::post($this->adivsorUrl.'/'.$endpoint, $headers, $body);
    }

    /**
     * Return the best advice for the player
     *
     * @param  Game $game
     *
     * @return int
     */
    public function getBestAdvice(Game $game): array
    {
        $body = $this->getBodyFromGame($game);
        $response = $this->post($body, 'best-move');

        return $this->getAdviceFromResponseBody($response->body);
    }

    /**
     * Return the best advice for the other player
     * Making it the worst advice for the current player
     *
     * @param  Game $game
     *
     * @return array
     */
    public function getWorstAdvice(Game $game): array
    {
        $body = $this->getBodyFromGame($game);
        $response = $this->post($body, 'worst-move');
        return $this->getAdviceFromResponseBody($response->body);
    }

    /**
     * Format stdClass to array
     *
     * @param  stdClass $body
     *
     * @return array
     */
    private function getAdviceFromResponseBody(\stdClass $body): array
    {
        return [
            'coordsStart' => [
                'x' => $body->CoordsStart->X,
                'y' => $body->CoordsStart->Y,
            ],
            'coordsEnd' => [
                'x' => $body->CoordsEnd->X,
                'y' => $body->CoordsEnd->Y,
            ],
        ];
    }

    private function getBodyFromGame(Game $game): string
    {
        $body = [
            'Grid' => $game->getBoard(),
            'Player' => $game->getCurrentPlayer(),
        ];
        $selectedCube = $game->getSelectedCube();
        // Set to -1 because go will init struct to 0 if null
        $cubeX = $selectedCube !== null ? $selectedCube['x'] : -1;
        $cubeY = $selectedCube !== null ? $selectedCube['y'] : -1;
        $value = $selectedCube !== null ? $game->getBoard()[$cubeX][$cubeY] : -1;
        $body['SelectedCube'] = [
            'Coords' => ['X' => $cubeX, 'Y' => $cubeY],
            'Value' => $value,
        ];

        return json_encode($body);
    }
}
