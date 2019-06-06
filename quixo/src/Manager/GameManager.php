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
     * @return Game
     */
    public function createGame(): Game
    {
        $game = new Game();
        $newBoard = $this->getEmptyBoard($game->getRows(), $game->getCols());
        $game->setBoard($newBoard);
        $this->gameRepository->save($game);

        return $game;
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
     * Set the selected cube in Game and save it
     *
     * @param  Game   $game
     * @param  Coords $coords
     *
     * @return Game
     */
    public function selectCube(Game $game, Coords $coords): Game
    {
        $game->setSelectedCube([
            'x' => $coords->getX(),
            'y' => $coords->getY(),
        ]);
        $this->gameRepository->save($game);

        return $game;
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
        $selectedCube = $game->getSelectedCube();
        $coordsStart = new Coords($selectedCube['x'], $selectedCube['y']);
        $newBoard = $this->moveCube($game, $coordsStart, $coords, $team);
        $game->setBoard($newBoard);

        $game->setSelectedCube(null);

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
     * Return an array of coords of the movables cubes
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
                    $movables[] = $coords;
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
    public function moveCube(Game $game, Coords $coordsStart, Coords $coordsEnd, int $value): array
    {
        $board = $game->getBoard();
        if ($coordsStart->getX() === $coordsEnd->getX()) {
            $board = $this->shiftRow($board, $value, [
                'rowIndex' => $coordsStart->getX(),
                'xStart' => $coordsStart->getY(),
                'xEnd' => $coordsEnd->getY(),
            ]);
        }
        elseif ($coordsStart->getY() === $coordsEnd->getY()) {
            $flippedBoard = $this->flipRowCol($board);
            $flippedBoard = $this->shiftRow($flippedBoard, $value, [
                'rowIndex' => $coordsStart->getY(),
                'xStart' => $coordsStart->getX(),
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

    /**
     * Get winning cubes
     *
     * @param  Game $game
     *
     * @return array with winner and an array of the winningCubes
     */
    public function resolveWinnerAndWinningCubes(Game $game): array
    {
        $currentPlayer = $game->getCurrentPlayer();
        $winningLines = $this->getWinningLines($game);
        $winner = null;
        $winCubes = [];
        foreach ($winningLines as $line) {
            $cube = $line[0];
            $winner = $cube->getValue();
            $winCubes = $line;
            if ($winner !== $currentPlayer) {
                break;
            }
        }
        return [$winner, $winCubes];
    }

    /**
     * Get winning lines
     *
     * @param  Game $game
     *
     * @return array with all winning lines
     */
    public function getWinningLines(Game $game): array
    {
        $lines = [];
        $lines[] = $this->getWinnerDiagonalLine($game);
        $lines[] = $this->getWinnerDiagonalLine($game, true);
        foreach ($this->getWinnerStraightLines($game) as $row) {
            $lines[] = $row;
        }
        foreach ($this->getWinnerStraightLines($game, true) as $col) {
            $lines[] = $col;
        }
        return array_filter($lines, function($line) {
            return count($line) > 0;
        });
    }

    /**
     * Get the winning cube if exist
     *
     * @param  Game  $game
     * @param  bool  $inverted
     *
     * @return array
     */
    public function getWinnerDiagonalLine(Game $game, bool $inverted = false): array
    {
        if ($game->getRows() !== $game->getCols()) {
            return [];
        }
        $board = $game->getBoard();
        $winCubes = [];
        $prevValue = null;
        for ($i = 0; $i < $game->getRows(); $i++) {
            $x = $i;
            $y = $inverted ? $game->getRows() - 1 - $i : $i;
            $value = $board[$x][$y];
            if ($prevValue === null) {
                $prevValue = $value;
            }
            if ($value === $prevValue && $value !== GameManager::NEUTRAL_VALUE) {
                $winCubes[] = new Cube(new Coords($x, $y), $value);
            } else {
                return [];
            }
        }
        return $winCubes;
    }

    /**
     * Get winning lines
     *
     * @param  Game $game
     * @param  bool $inverted
     *
     * @return array
     */
    public function getWinnerStraightLines(Game $game, $inverted = false): array
    {
        $board = $inverted ? $this->flipRowCol($game->getBoard()) : $game->getBoard();
        $winningRows = [];
        for ($x = 0; $x < count($board); $x++) {
            $row = [];
            $prevValue = null;
            for ($y = 0; $y < count($board[$x]); $y++) {
                $value = $board[$x][$y];
                if ($prevValue === null) {
                    $prevValue = $value;
                }
                if ($value === $prevValue && $value !== GameManager::NEUTRAL_VALUE) {
                    $coords = $inverted ? new Coords($y, $x) : new Coords($x, $y);
                    $row[] = new Cube($coords, $value);
                } else {
                    break;
                }
            }
            if (count($row) === count($board[$x])) {
                $winningRows[] = $row;
            }
        }
        return $winningRows;
    }

    /**
     * Persist the winner
     *
     * @param  Game $game
     * @param  int  $winner
     *
     * @return void
     */
    public function persistWinner(Game $game, int $winner): void
    {
        $game->setWinner($winner);
        $this->gameRepository->save($game);
    }

    /**
     * Return the coords of the allowed destinations from the selected cube
     *
     * @param  Game $game
     *
     * @return array
     */
    public function getAllowedDestinations(Game $game): array
    {
        $selectedCube = $game->getSelectedCube();
        if ($selectedCube === null || !isset($selectedCube['x']) || !isset($selectedCube['y'])) {
            return [];
        }
        $x = $selectedCube['x'];
        $y = $selectedCube['y'];
        $indexLastRow = $game->getRows() - 1;
        $indexLastCol = $game->getCols() - 1;
        $destinations = ($x === 0 || $x === $indexLastRow)
            ? $this->getDestinationsForOutsideRow($x, $y, $indexLastRow, $indexLastCol)
            : $this->getDestinationsForOutsideCol($x, $y, $indexLastRow, $indexLastCol);
        return $destinations;
    }

    /**
     * Get destinations for a cube of x and y coords if it's on an oustide row
     *
     * @param int $x
     * @param int $y
     * @param int $lastRow
     * @param int $lastCol
     *
     * @return array
     */
    public function getDestinationsForOutsideRow($x, $y, $lastRow, $lastCol): array
    {
        if ($x !== 0 && $x !== $lastRow) {
            return [];
        }
        $destinations = [];
        if ($y !== 0) {
            $destinations[] = new Coords($x, 0);
        }
        if ($y !== $lastCol) {
            $destinations[] = new Coords($x, $lastCol);
        }
        $oppositeX = $x === $lastRow ? 0 : $lastRow;
        $destinations[] = new Coords($oppositeX, $y);

        return $destinations;
    }

    /**
     * Get destinations for a cube of x and y coords if it's on an oustide col
     *
     * @param int $x
     * @param int $y
     * @param int $lastRow
     * @param int $lastCol
     *
     * @return array
     */
    public function getDestinationsForOutsideCol($x, $y, $lastRow, $lastCol): array
    {
        if ($y === 0 && $y === $lastCol) {
            return [];
        }
        $destinations = [];
        if ($x !== 0) {
            $destinations[] = new Coords(0, $y);
        }
        if ($x !== $lastRow) {
            $destinations[] = new Coords($lastRow, $y);
        }
        $oppositeY = $y === $lastCol ? 0 : $lastCol;
        $destinations[] = new Coords($x, $oppositeY);

        return $destinations;
    }

    /**
     * Get movables or destinations if a cube has been selected or not
     *
     * @param  Game $game
     *
     * @return array
     */
    public function getMovablesOrDestinations(Game $game): array
    {
        return $game->getSelectedCube() === null
            ? $this->getMovables($game, $game->getCurrentPlayer())
            : $this->getAllowedDestinations($game);
    }
}
