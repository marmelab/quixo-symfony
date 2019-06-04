<?php

namespace App\Utils;

class GameUtils
{
    const NEUTRAL_VALUE = 0;
    const CROSS_TEAM = 1;
    const CIRCLE_TEAM = -1;

    const N_ROWS = 5;
    const N_COLS = 5;

    /**
     * getEmptyBoard
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
}
