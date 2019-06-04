<?php
namespace App\Tests\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\GameUtils;

class GameUtilsTest extends TestCase
{

    /**
     * testGetEmptyBoard
     *
     * @return void
     */
    public function testGetEmptyBoard(): void
    {
        $emptyBoard = GameUtils::getEmptyBoard();
        $this->assertEquals(count($emptyBoard), GameUtils::N_ROWS);
        $this->assertEquals(count($emptyBoard[0]), GameUtils::N_COLS);

        $n_rows = 10;
        $n_cols = 8;
        $emptyRectBoard = GameUtils::getEmptyBoard($n_rows, $n_cols);
        $this->assertEquals(count($emptyRectBoard), $n_rows);
        $this->assertEquals(count($emptyRectBoard[0]), $n_cols);
    }
}
