<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;

class GameRepository extends EntityRepository
{

    protected $em;

    /**
     * __construct
     *
     * @param  EntityManagerInterface $em
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata('App:Game'));
        $this->em = $em;
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
