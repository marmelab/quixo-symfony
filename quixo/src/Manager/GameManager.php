<?php

namespace App\Manager;

use App\Entity\Coords;
use App\Entity\Cube;

class GameManager
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
    public function getEmptyBoard(int $n_rows=self::N_ROWS, int $n_cols=self::N_COLS): array
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
    public function getMovables(array $board, int $team=self::NEUTRAL_VALUE): array
    {
        $movables = [];
        for ($x=0; $x < count($board); $x++) {
            for ($y=0; $y < count($board[$x]); $y++) {
                $coords = new Coords($x, $y);
                $cube = new Cube($coords, $board[$x][$y]);
                if ($this->isMovableCube($cube, $team)) {
                    $movables[] = $cube;
                }
            }
        }
        return $movables;
    }


    /**
     * return true if the cube is on the edge of the board
     *
     * @param  Coords $coords
     *
     * @return bool
     */
    public function isOutsideCube(Coords $coords): bool
    {
        return
            $coords->x === 0 ||
            $coords->x === self::N_ROWS -1 ||
            $coords->y === 0 ||
            $coords->y === self::N_COLS - 1;
    }

    /**
     * Return true if the cube is movable by the player
     *
     * @param  Cube  $cube
     * @param  int   $team
     *
     * @return bool
     */
    public function isMovableCube(Cube $cube, int $team): bool
    {
        $isAllowedCube = $cube->getValue() === 0 || $cube->getValue() === $team;
        return $this->isOutsideCube($cube->getCoords()) && $isAllowedCube;
    }


    /**
     * Move a cube from given coords
     *
     * @param  array  $board
     * @param  Coords $coords
     * @param  int    $value
     *
     * @return array
     */
    public function moveCube(array $board, Coords $coords, int $value): array
    {
        $coordsEnd = $this->getOppositeCube($coords);
        if ($coords->x === $coordsEnd->x) {
            $board = $this->shiftRow($board, $value, [
                'rowIndex' => $coords->x,
                'xStart' => $coords->y,
                'xEnd' => $coordsEnd->y,
            ]);
        }
        elseif ($coords->y === $coordsEnd->y) {
            $flippedBoard = $this->flipRowCol($board);
            $flippedBoard = $this->shiftRow($flippedBoard, $value, [
                'rowIndex' => $coords->y,
                'xStart' => $coords->x,
                'xEnd' => $coordsEnd->x,
            ]);
            $board = $this->flipRowCol($flippedBoard);
        }
        return $board;
    }


    /**
     * Shift the row from xStart to xEnd
     *
     * @param  mixed $board
     * @param  mixed $value
     * @param  mixed $coords
     *
     * @return array
     */
    public function shiftRow(array $board, int $value, array $coords): array
    {
        $rowIndex = $coords['rowIndex'];
        $xStart = $coords['xStart'];
        $xEnd = $coords['xEnd'];

        $step = $xEnd > $xStart ? -1 : 1;
        $indexStop = $xEnd > $xStart ? $xStart - 1 : $xStart + 1;
        for ($i = $xEnd; $i !== $indexStop; $i += $step) {
            $tmpVal = $board[$rowIndex][$i];
            $board[$rowIndex][$i] = $value;
            $value = $tmpVal;
        }
        return $board;
    }

    /**
     * Get the opposite cube
     * This is a tmp function waiting for the player to choose himself the direction
     *
     * @param  Coords $coords
     *
     * @return Coords
     */
    public function getOppositeCube(Coords $coords): Coords
    {
        $x = $coords->x;
        $y = $coords->y;

        $xEnd = $x;
        $yEnd = $y;

        if ($x === 0) {
            $xEnd = self::N_ROWS - 1;
        }
        if ($x === self::N_ROWS - 1) {
            $xEnd = 0;
        }
        if ($y === 0) {
            $yEnd = self::N_COLS - 1;
        }
        if ($y === self::N_COLS - 1) {
            $yEnd = 0;
        }
        return new Coords($xEnd, $yEnd);
    }

    /**
     * Flip the cols and rows of a 2d array
     *
     * @param  array $array
     *
     * @return array
     */
    public function flipRowCol(array $array): array {
        $out = array();
        foreach ($array as $rowkey => $row) {
            foreach($row as $colkey => $col){
                $out[$colkey][$rowkey]=$col;
            }
        }
        return $out;
    }
}
