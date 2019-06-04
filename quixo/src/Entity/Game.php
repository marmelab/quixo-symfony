<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ORM\Table
 */
class Game
{
    const N_ROWS = 5;
    const N_COLS = 5;

    const NEUTRAL_VALUE = 0;
    const CROSS_TEAM = 1;
    const CIRCLE_TEAM = -1;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="json_array") */
    private $board;

    public function __construct(
        int $id,
        array $board
    ) {
        $this->id = $id;
        $this->board = $board;
    }

    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
        return $this;
    }

    public function getBoard() {
        return $this->board;
    }

    public function setBoard(array $board) {
        $this->board = $board;
        return $this;
    }

    public static function getEmptyBoard() {
        $board = [];
        for ($x = 0; $x < self::N_ROWS; $x++) {
            for ($y =0 ; $y < self::N_COLS; $y++) {
                $board[$x][$y] = self::NEUTRAL_VALUE;
            }
        }
        return $board;
    }
}
