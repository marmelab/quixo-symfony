<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
}
