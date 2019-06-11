<?php

namespace App\Domain;

use App\Manager\GameManager;

class TeamSelection
{
    private $team;

    public function __construct(int $team = GameManager::NEUTRAL_VALUE)
    {
        $this->team = $team;
    }

    /**
     * Get the value of team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set the value of team
     *
     * @return  self
     */
    public function setTeam($team)
    {
        $this->team = $team;

        return $this;
    }
}
