<?php

namespace App\Utils;

use App\Entity\Cube;
use App\Entity\Coords;

class GameUtils
{
    const NEUTRAL_VALUE = 0;
    const CROSS_TEAM = 1;
    const CIRCLE_TEAM = -1;

    const N_ROWS = 5;
    const N_COLS = 5;

    /**
     * Get an empty board of n_rows and n_cols
     *
     * @param  int $n_rows
     * @param  int $n_cols
     *
     * @return array
     */
    public static function getEmptyBoard(int $n_rows=self::N_ROWS, int $n_cols=self::N_COLS): array
    {
        $board = [];
        for ($x = 0; $x < $n_rows; $x++) {
            for ($y =0 ; $y < $n_cols; $y++) {
                $board[$x][$y] = self::NEUTRAL_VALUE;
            }
        }
        return $board;
    }

    /**
     * Return an array of movables cubes
     *
     * @param  array $board
     * @param  int   $team
     *
     * @return array
     */
    public static function getMovables(array $board, int $team=self::NEUTRAL_VALUE): array
    {
        $movables = [];
        for ($x=0; $x < count($board); $x++) {
            for ($y=0; $y < count(board[$x]); $y++) {
                $coords = new Coords($x, $y);
                $cube = new Cube($coords, $board[x][y]);
                if (self::isMovableCube($cube, $team)) {
                    $movables[] = $cube;
                }
            }
        }
        return $movables;
    }


    /**
     * return true if the cube is on the edge of the board
     *
     * @param  Cube $cube
     *
     * @return bool
     */
    public static function isOutsideCube(Cube $cube): bool
    {
        $coord = $cube->getCoord();
        return
            $coord['x'] === 0 ||
            $coord['x'] === self::N_ROWS -1 ||
            $coord['y'] === 0 ||
            $coord['y'] === self::N_COLS - 1;
    }

    /**
     * Return true if the cube is movable by the player
     *
     * @param  Cube  $cube
     * @param  int   $team
     *
     * @return bool
     */
    public static function isMovableCube(Cube $cube, int $team): bool
    {
        $isAllowedCube = $cube->getValue() === 0 or $cube->getValue() === $team;
        return self::isOutsideCube($cube) && isAllowedCube;
    }
}
