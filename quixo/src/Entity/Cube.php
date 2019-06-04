<?php

namespace App\Entity;

use App\Utils\GameUtils;

class Cube
{
    private $coord;

    private $value;

    private $movable;

    private $selected;

    public function __construct(array $coord, $value=GameUtils::NEUTRAL_VALUE, $movable=false, $selected=false)
    {
        $this->coord = $coord;
        $this->value = $value;
        $this->movable = $movable;
        $this->selected = $selected;
    }

    /**
     * Get the value of selected
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * Set the value of selected
     *
     * @return  self
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get the value of movable
     */
    public function getMovable()
    {
        return $this->movable;
    }

    /**
     * Set the value of movable
     *
     * @return  self
     */
    public function setMovable($movable)
    {
        $this->movable = $movable;

        return $this;
    }

    /**
     * Get the value of value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @return  self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get the value of coord
     */
    public function getCoord()
    {
        return $this->coord;
    }

    /**
     * Set the value of coord
     *
     * @return  self
     */
    public function setCoord($coord)
    {
        $this->coord = $coord;

        return $this;
    }
}
