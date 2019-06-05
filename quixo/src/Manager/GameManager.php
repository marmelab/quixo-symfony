<?php

namespace App\Manager;

use App\Entity\Coords;
use App\Entity\Cube;
use App\Repository\GameRepository;
use App\Entity\Game;

class GameManager
{
    const NEUTRAL_VALUE = 0;
    const CROSS_TEAM = 1;
    const CIRCLE_TEAM = -1;

    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * Create a new game and return id
     *
     * @return int the id of the game
     */
    public function createGame(): int
    {
        $game = new Game();
        $newBoard = $this->getEmptyBoard($game->getRows(), $game->getCols());
        $game->setBoard($newBoard);
        $this->gameRepository->save($game);

        return $game->getId();
    }

    /**
     * Fetch game from DB by id
     *
     * @param  int $id
     *
     * @return Game
     */
    public function getGame(int $id): Game
    {
        return $this->gameRepository->find($id);
    }

    /**
     * Play a cube and save the game
     *
     * @param  Game   $game
     * @param  Coords $coords
     * @param  int    $team
     *
     * @return Game
     */
    public function playCube(Game $game, Coords $coords, int $team): Game
    {
        $newBoard = $this->moveCube($game, $coords, $team);
        $game->setBoard($newBoard);
        $this->gameRepository->save($game);

        return $game;
    }

    /**
     * Get an empty board of n_rows and n_cols
     *
     * @param  int $n_rows
     * @param  int $n_cols
     *
     * @return array
     */
    public function getEmptyBoard(int $n_rows, int $n_cols): array
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
     * @param  Game  $game
     * @param  int   $team
     *
     * @return array
     */
    public function getMovables(Game $game, int $team=self::NEUTRAL_VALUE): array
    {
        $board = $game->getBoard();
        $movables = [];
        for ($x=0; $x < count($board); $x++) {
            for ($y=0; $y < count($board[$x]); $y++) {
                $coords = new Coords($x, $y);
                $cube = new Cube($coords, $board[$x][$y]);
                if ($this->isMovableCube($game, $cube, $team)) {
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
    public function isOutsideCube(Game $game, Coords $coords): bool
    {
        return
            $coords->getX() === 0 ||
            $coords->getX() === $game->getRows() -1 ||
            $coords->getY() === 0 ||
            $coords->getY() === $game->getCols() - 1;
    }

    /**
     * Return true if the cube is movable by the player
     * @param  Game  $game
     * @param  Cube  $cube
     * @param  int   $team
     *
     * @return bool
     */
    public function isMovableCube(Game $game, Cube $cube, int $team): bool
    {
        $isAllowedCube = $cube->getValue() === 0 || $cube->getValue() === $team;
        return $this->isOutsideCube($game, $cube->getCoords()) && $isAllowedCube;
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
    public function moveCube(Game $game, Coords $coords, int $value): array
    {
        $board = $game->getBoard();
        $coordsEnd = $this->getOppositeCube($game, $coords);
        if ($coords->getX() === $coordsEnd->getX()) {
            $board = $this->shiftRow($board, $value, [
                'rowIndex' => $coords->getX(),
                'xStart' => $coords->getY(),
                'xEnd' => $coordsEnd->getY(),
            ]);
        }
        elseif ($coords->getY() === $coordsEnd->getY()) {
            $flippedBoard = $this->flipRowCol($board);
            $flippedBoard = $this->shiftRow($flippedBoard, $value, [
                'rowIndex' => $coords->getY(),
                'xStart' => $coords->getX(),
                'xEnd' => $coordsEnd->getX(),
            ]);
            $board = $this->flipRowCol($flippedBoard);
        }
        return $board;
    }


    /**
     * Shift the row from xStart to xEnd
     *
     * @param  mixed  $board
     * @param  int    $value
     * @param  array  $coords
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
     * @param  Game   $game
     * @param  Coords $coords
     *
     * @return Coords
     */
    public function getOppositeCube(Game $game, Coords $coords): Coords
    {
        $x = $coords->getX();
        $y = $coords->getY();

        $xEnd = $x;
        $yEnd = $y;

        if ($x === 0) {
            $xEnd = $game->getRows() - 1;
        }
        if ($x === $game->getRows() - 1) {
            $xEnd = 0;
        }
        if ($y === 0) {
            $yEnd = $game->getCols() - 1;
        }
        if ($y === $game->getCols() - 1) {
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
