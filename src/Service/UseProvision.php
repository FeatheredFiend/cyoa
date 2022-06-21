<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\GetHero;

class UseProvision
{
    private $useProvision;
    private $getHero;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $useProvision, GetHero $getHero)
    {
        $this->useProvision = $useProvision;
        $this->getHero = $getHero;
        $this->entityManager = $entityManager;

    }
    
    public function gainProvision($adventure, $provision)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);

        $RAW_QUERY = "UPDATE hero SET provision = provision + :provision WHERE hero_id = :hero";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('provision', $provision);
        $statement->execute();

    }   
    
    public function removeProvision($adventure, $provision)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);

        $RAW_QUERY = "UPDATE hero SET provision = provision - :provision WHERE hero_id = :hero";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('provision', $provision);
        $statement->execute();

    }

    public function getUseProvision()
    {
        return $this->useProvision;
    }

}