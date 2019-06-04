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

    public function __construct(
        array $board
    ) {
        $this->board = $board;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function setBoard(array $board)
    {
        $this->board = $board;
        return $this;
    }
}
