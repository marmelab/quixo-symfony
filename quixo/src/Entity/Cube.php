<?php

namespace App\Entity;

use App\Entity\Coords;
use App\Manager\GameManager;

class Cube
{
    private $coords;

    private $value;

    public function __construct(Coords $coords, $value = GameManager::NEUTRAL_VALUE)
    {
        $this->coords = $coords;
        $this->value = $value;
    }

    /**
     * Get the value of value
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @return  self
     */
    public function setValue(int $value): Cube
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get the value of coord
     */
    public function getCoords(): Coords
    {
        return $this->coords;
    }

    /**
     * Set the value of coord
     *
     * @return  self
     */
    public function setCoord(Coords $coords): Cube
    {
        $this->coords = $coords;

        return $this;
    }
}
