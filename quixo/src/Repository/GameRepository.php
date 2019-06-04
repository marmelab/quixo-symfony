<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;

class GameRepository extends EntityRepository
{

    private $em;

    /**
     * __construct
     *
     * @param  EntityManagerInterface $em
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * findById
     *
     * @param  int $id
     *
     * @return Game
     */
    public function findById(int $id): Game
    {
        return $this->em->find('App:Game', $id);
    }

    /**
     * remove
     *
     * @param  int $id
     *
     * @return void
     */
    public function remove(int $id): void
    {
        $game = $this->em->getReference('App:Game', $id);
        $this->em->remove($game);
        $this->flush();
    }

    /**
     * save
     *
     * @param  Game $game
     *
     * @return void
     */
    public function save(Game $game): void
    {
        $this->em->persist($game);
        $this->em->flush();
    }
}
