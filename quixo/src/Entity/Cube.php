<?php

namespace App\Entity;

use App\Utils\GameUtils;
use App\Entity\Coords;

class Cube
{
    private $coords;

    private $value;

    public function __construct(Coords $coords, $value=GameUtils::NEUTRAL_VALUE)
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
    public function getCoord(): Coords
    {
        return $this->coord;
    }

    /**
     * Set the value of coord
     *
     * @return  self
     */
    public function setCoord(Coords $coord): Cube
    {
        $this->coord = $coord;

        return $this;
    }
}
