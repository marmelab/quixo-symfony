<?php
namespace App\Tests\Utils;

use PHPUnit\Framework\TestCase;
use App\Manager\GameManager;
use App\Repository\GameRepository;
use App\Entity\Game;
use App\Entity\Coords;

class GameUtilsTest extends TestCase
{

    /**
     * Test that game manager return an empty board with the good numbers of rows & cols
     *
     * @return void
     */
    public function testGetEmptyBoard(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);

        $n_rows = 10;
        $n_cols = 8;
        $emptyRectBoard = $manager->getEmptyBoard($n_rows, $n_cols);
        $this->assertEquals(count($emptyRectBoard), $n_rows);
        $this->assertEquals(count($emptyRectBoard[0]), $n_cols);
    }

    /**
     * Test that game manager return a correct list of movables cubes
     *
     * @return void
     */
    public function testGetMovables(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);
        $game = new Game();
        $game->setBoard($manager->getEmptyBoard($game->getRows(), $game->getCols()));

        $expected_movables = [
            ['x' => 0, 'y'=> 0], ['x' => 0, 'y'=> 1], ['x' => 0, 'y'=> 2], ['x' => 0, 'y'=> 3], ['x' => 0, 'y'=> 4],
            ['x' => 1, 'y'=> 0], ['x' => 1, 'y'=> 4], ['x' => 2, 'y'=> 0],
            ['x' => 2, 'y'=> 4], ['x' => 3, 'y'=> 0], ['x' => 3, 'y'=> 4],
            ['x' => 4, 'y'=> 0], ['x' => 4, 'y'=> 1], ['x' => 4, 'y'=> 2], ['x' => 4, 'y'=> 3], ['x' => 4, 'y'=> 4]
        ];
        $found = 0;
        $movables = $manager->getMovables($game);

        foreach ($movables as $coords) {
            foreach ($expected_movables as $expected) {
                if ($coords->getX() == $expected['x'] && $coords->getY() == $expected['y']) {
                    $found++;
                    break;
                }
            }
        }
        $this->assertEquals(count($expected_movables), $found, count($movables));
    }

    /**
     * Test that shift row add the correct value to the cube
     *
     * @return void
     */
    public function testShiftRow(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);

        $initRow = [[0, 0, 0, 0, 0]];
        $expectedRow = [[0, 0, 0, 0, 1]];
        $row = $manager->shiftRow($initRow, 1, [
            'rowIndex' => 0,
            'xStart' => 0,
            'xEnd' => 4,
        ]);
        $this->assertEquals($expectedRow, $row);
    }

    /**
     * Test that shift row use the existing cube
     *
     * @return void
     */
    public function testShiftRowWithExistingCube(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);

        $initRow = [[1, 0, 0, 0, 0]];
        $expectedRow = [[0, 0, 0, 0, 1]];
        $row = $manager->shiftRow($initRow, 1, [
            'rowIndex' => 0,
            'xStart' => 0,
            'xEnd' => 4,
        ]);
        $this->assertEquals($expectedRow, $row);
    }

    /**
     * Check the moveCube move the cube and shift the line
     *
     * @return void
     */
    public function testMoveCube(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);
        $initBoard = [
            [0, 1, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0],
            [0, -1, 0, 0, 0]
        ];
        $expectedBoard = [
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0],
            [0, -1, 0, 0, 0],
            [0, 1, 0, 0, 0]
        ];
        $game = new Game();
        $game->setBoard($initBoard);

        $cubeStart = new Coords(0, 1);
        $cubeEnd = new Coords(4, 1);
        $board = $manager->moveCube($game, $cubeStart, $cubeEnd, 1);
        $this->assertEquals($board, $expectedBoard);
    }

    /**
     * Test that no winner is returned when board has no winner
     *
     * @return void
     */
    public function testGameHasNoWinner(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);
        $game = new Game();
        $noWinBoard = [
            [0, 1, 1, 1, 1],
            [1, 1, 0, 1, 1],
            [1, 0, 1, 1, 1],
            [0, 1, 0, 1, 0],
            [0, 1, 1, 0, 0]
        ];
        $game->setBoard($noWinBoard);
        list($winner, $winningCubes) = $manager->resolveWinnerAndWinningCubes($game);

        $this->assertEquals(null, $winner);
        $this->assertEquals(count($winningCubes), 0);
    }

    /**
     * Test that a winner and the cubes are returned when a winner exist
     *
     * @return void
     */
    public function testGameHasWinner(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);
        $game = new Game();
        $winBoard = [
            [0, 0, 0, 0, 0],
            [1, 1, 1, 1, 1],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0]
        ];
        $game->setBoard($winBoard);
        list($winner, $winningCubes) = $manager->resolveWinnerAndWinningCubes($game);

        $this->assertEquals(1, $winner);
        $this->assertEquals(count($winningCubes), 5);
    }

    /**
     * Test that the winner is the non-playing player when draw
     *
     * @return void
     */
    public function testNoPlayingPlayerWinWhenDraw(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);
        $game = new Game();
        $player = 1;
        $winningPlayer = -1;
        $winBoard = [
            [0, 0, 0, 0, 0],
            [$player, $player, $player, $player, $player],
            [$winningPlayer, $winningPlayer, $winningPlayer, $winningPlayer, $winningPlayer],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0]
        ];
        $game->setBoard($winBoard);
        list($winner, $winningCubes) = $manager->resolveWinnerAndWinningCubes($game);

        $this->assertEquals($winningPlayer, $winner);
        $this->assertEquals(count($winningCubes), 5);
    }

    /**
     * Test that a winner and the cubes are returned when a winner exist in a diag
     *
     * @return void
     */
    public function testGameHasWinInDiag(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);
        $game = new Game();
        $winBoard = [
            [1, 0, 0, 0, 0],
            [0, 1, 0, 0, 0],
            [0, 0, 1, 0, 0],
            [0, 0, 0, 1, 0],
            [0, 0, 0, 0, 1]
        ];
        $game->setBoard($winBoard);
        list($winner, $winningCubes) = $manager->resolveWinnerAndWinningCubes($game);

        $this->assertEquals(1, $winner);
        $this->assertEquals(count($winningCubes), 5);
    }

    public function testGetAllowedDestinationsFromCorner(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);
        $game = new Game();
        $board = [
            [1, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0]
        ];
        $game->setBoard($board);
        $game->setSelectedCube(['x' => 0, 'y' => 0]);
        $expectedDestinations = [new Coords(0, 4), new Coords(4, 0)];
        $destinations = $manager->getAllowedDestinations($game);

        $this->checkArrayOfCoordsAreEquals($expectedDestinations, $destinations);
    }

    public function testGetAllowedDestinationsFromRow(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);
        $game = new Game();
        $board = [
            [0, 1, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0]
        ];
        $game->setBoard($board);
        $game->setSelectedCube(['x' => 0, 'y' => 1]);
        $expectedDestinations = [new Coords(0, 0), new Coords(0, 4), new Coords(4, 1)];
        $destinations = $manager->getAllowedDestinations($game);

        $this->checkArrayOfCoordsAreEquals($expectedDestinations, $destinations);
    }

    public function testGetAllowedDestinationsFromCol(): void
    {
        $gameRepository = $this->createMock(GameRepository::class);
        $manager = new GameManager($gameRepository);
        $game = new Game();
        $board = [
            [0, 0, 0, 0, 0],
            [1, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0]
        ];
        $game->setBoard($board);
        $game->setSelectedCube(['x' => 1, 'y' => 0]);
        $expectedDestinations = [new Coords(0, 0), new Coords(4, 0), new Coords(1, 4)];
        $destinations = $manager->getAllowedDestinations($game);

        $this->checkArrayOfCoordsAreEquals($expectedDestinations, $destinations);
    }

    private function checkArrayOfCoordsAreEquals(array $array1, array $array2): void
    {
        $this->assertEquals(count($array1), count($array2));

        foreach ($array1 as $dest1) {
            $found = false;
            foreach ($array2 as $dest2) {
                if ($dest1->getX() === $dest2->getX() && $dest1->getY() === $dest2->getY()) {
                    $found = true;
                }
            }
            $this->assertTrue($found);
        }
    }
}
