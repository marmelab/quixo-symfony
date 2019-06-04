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

    /**
     * __construct
     *
     * @param  int $id
     *
     * @return void
     */
    public function __construct(array $board)
    {
        $this->board = $board;
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
}
