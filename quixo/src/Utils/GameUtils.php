<?php

namespace App\Utils;

use App\Entity\Cube;

class GameUtils
{
    const N_ROWS = 5;
    const N_COLS = 5;

    public static function getEmptyBoard(int $n_rows=self::N_ROWS, int $n_cols=self::N_COLS): array
    {
        $board = [];
        for ($x = 0; $x < $n_rows; $x++) {
            for ($y =0 ; $y < $n_cols; $y++) {
                $board[$x][$y] = Cube::NEUTRAL_VALUE;
            }
        }
        return $board;
    }
}
