<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;

class GameRepository extends EntityRepository
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findById($id)
    {
        return $this->em->find('App:Game', $id);
    }

    public function remove($id)
    {
        $game = $this->em->getReference('App:Game', $id);
        $this->em->remove($game);
        $this->flush();
    }

    public function save(Game $game)
    {
        $this->em->persist($game);
        $this->em->flush();
    }
}
