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
    private $selectedCube;

    /** @ORM\Column(type="integer") */
    private $numberOfPlayers;

    /** @ORM\Column(type="integer", nullable=true) */
    private $player1;

    /** @ORM\Column(type="integer", nullable=true) */
    private $player2;

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
        $this->numberOfPlayers = 0;
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
    public function getWinner(): ?int
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

    /**
     * Get the value of selectedCube
     */
    public function getSelectedCube(): ?array
    {
        return $this->selectedCube;
    }

    /**
     * Set the value of selectedCube
     *
     * @return  self
     */
    public function setSelectedCube($selectedCube): Game
    {
        $this->selectedCube = $selectedCube;

        return $this;
    }

    /**
     * Get the value of numberOfPlayers
     */
    public function getNumberOfPlayers()
    {
        return $this->numberOfPlayers;
    }

    /**
     * Set the value of numberOfPlayers
     *
     * @return  self
     */
    public function setNumberOfPlayers($numberOfPlayers)
    {
        $this->numberOfPlayers = $numberOfPlayers;

        return $this;
    }

    /**
     * Get the value of player1
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * Set the value of player1
     *
     * @return  self
     */
    public function setPlayer1($player1)
    {
        $this->player1 = $player1;

        return $this;
    }

    /**
     * Get the value of player2
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * Set the value of player2
     *
     * @return  self
     */
    public function setPlayer2($player2)
    {
        $this->player2 = $player2;

        return $this;
    }
}
