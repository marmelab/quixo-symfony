<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Manager\GameManager;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ORM\Table
 */
class Game
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="json") */
    private $board;

    /** @ORM\Column(type="integer") */
    private $rows;

    /** @ORM\Column(type="integer") */
    private $cols;

    /** @ORM\Column(type="integer", nullable=true) */
    private $winner;

    /** @ORM\Column(type="integer") */
    private $currentPlayer;

    /** @ORM\Column(type="json", nullable=true) */
    private $winningLine;

    /**
     * __construct
     *
     * @param  int $id
     *
     * @return void
     */
    public function __construct($rows = 5, $cols = 5)
    {
        $this->rows = $rows;
        $this->cols = $cols;
        $this->currentPlayer = GameManager::CROSS_TEAM;
    }

    /**
     * getId
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * setId
     *
     * @param  int $id
     *
     * @return Game
     */
    public function setId(int $id): Game
    {
        $this->id = $id;
        return $this;
    }

    /**
     * getBoard
     *
     * @return array
     */
    public function getBoard(): array
    {
        return $this->board;
    }

    /**
     * setBoard
     *
     * @param  array $board
     *
     * @return void
     */
    public function setBoard(array $board): Game
    {
        $this->board = $board;
        return $this;
    }

    /**
     * Get the value of rows
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Set the value of rows
     *
     * @return  self
     */
    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * Get the value of cols
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * Set the value of cols
     *
     * @return  self
     */
    public function setCols($cols)
    {
        $this->cols = $cols;

        return $this;
    }

    /**
     * Get the value of currentPlayer
     *
     * @return int
     */
    public function getCurrentPlayer(): int
    {
        return $this->currentPlayer;
    }

    /**
     * Set the value of currentPlayer
     *
     * @param  int $currentPlayer
     *
     * @return  self
     */
    public function setCurrentPlayer(int $currentPlayer): Game
    {
        $this->currentPlayer = $currentPlayer;

        return $this;
    }

    /**
     * Get the value of winner
     *
     * @return int
     */
    public function getWinner(): int
    {
        return $this->winner;
    }

    /**
     * Set the value of winner
     *
     * @param  int $winner
     *
     * @return self
     */
    public function setWinner(int $winner): Game
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get the value of winningLine
     */
    public function getWinningLine()
    {
        return $this->winningLine;
    }

    /**
     * Set the value of winningLine
     *
     * @return  self
     */
    public function setWinningLine($winningLine)
    {
        $this->winningLine = $winningLine;

        return $this;
    }
}
