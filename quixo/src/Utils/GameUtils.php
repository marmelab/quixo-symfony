<?php

namespace App\Utils;

use App\Entity\Cube;

class GameUtils
{
    const N_ROWS = 5;

    const N_COLS = 5;

    public static function getEmptyBoard()
    {
        $board = [];
        for ($x = 0; $x < self::N_ROWS; $x++) {
            for ($y =0 ; $y < self::N_COLS; $y++) {
                $board[$x][$y] = Cube::NEUTRAL_VALUE;
            }
        }
        return $board;
    }
}
