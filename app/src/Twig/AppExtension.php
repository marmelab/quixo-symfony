<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Manager\GameManager;
use App\Entity\Game;

class AppExtension extends AbstractExtension
{
    /**
     * getFilters
     *
     * @return void
     */
    public function getFilters()
    {
        return [
            new TwigFilter('getCubeSymbol', [$this, 'getCubeSymbol']),
            new TwigFilter('isCoordsInCubeArray', [$this, 'isCoordsInCubeArray']),
            new TwigFilter('isCoordsInCoordsArray', [$this, 'isCoordsInCoordsArray']),
            new TwigFilter('getPlayerName', [$this, 'getPlayerName']),
            new TwigFilter('getGameMessage', [$this, 'getGameMessage']),
        ];
    }

    /**
     * Return the symbol of the cube
     *
     * @param  int $value
     *
     * @return string
     */
    public function getCubeSymbol(int $value): string
    {
        if ($value === GameManager::CIRCLE_TEAM) {
            return 'circle';
        }
        if ($value === GameManager::CROSS_TEAM) {
            return 'cross';
        }
        return 'neutral';
    }

    /**
     * Check if cube is in coords array
     *
     * @param  array $movables
     * @param  int   $x
     * @param  int   $y
     *
     * @return bool
     */
    public function isCoordsInCoordsArray(array $movables, int $x, int $y): bool
    {
        foreach ($movables as $coords) {
            if ($coords->getX() === $x && $coords->getY() === $y) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if cube is in cubes array
     *
     * @param  array $movables
     * @param  int   $x
     * @param  int   $y
     *
     * @return bool
     */
    public function isCoordsInCubeArray(array $movables, int $x, int $y): bool
    {
        foreach ($movables as $cube) {
            $coords = $cube->getCoords();
            if ($coords->getX() === $x && $coords->getY() === $y) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the player name
     *
     * @param  int $value
     *
     * @return string
     */
    public function getPlayerName(int $value): string
    {
        return $value === 1 ? 'Player 1' : 'Player 2';
    }

    public function getGameMessage(Game $game, bool $waitForPlayer): string
    {
        if ($game->getWinner() !== null) {
            return $this->getPlayerName($game->getWinner()) . ' has won the game !';
        }
        return $waitForPlayer
            ? 'Waiting for ' . $this->getPlayerName($game->getCurrentPlayer())
            : "It's your turn !";
    }
}
