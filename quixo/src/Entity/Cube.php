<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

class Cube
{
    const NEUTRAL_VALUE = 0;

    const CROSS_TEAM = 1;

    const CIRCLE_TEAM = -1;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    private $movable;

    private $selected;

    public function __construct($value=self::NEUTRAL_VALUE, $movable=false, $selected=false)
    {
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
}
