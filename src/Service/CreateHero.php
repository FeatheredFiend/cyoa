<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class CreateHero
{
    private $createHero;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $createHero)
    {
        $this->createHero = $createHero;
        $this->entityManager = $entityManager;

    }

    
    public function createHero($name)
    {
        $em = $this->entityManager;

        $startingskill = 6 + rand(1,6);
        $startingstamina = 12 + rand(2,12);
        $startingluck = 6 + rand(1,6);
        $honour = 6;
        $startingprovision = 10;

        $RAW_QUERY = "INSERT INTO hero(name, skill, stamina, luck, honour, startingskill, startingstamina, startingluck, startingprovision, provision) VALUES (:name, :startingskill, :startingstamina, :startingluck, :honour, :startingskill, :startingstamina, :startingluck, :startingprovision, :startingprovision)";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('name', $name);
        $statement->bindParam('startingskill', $startingskill);
        $statement->bindParam('startingstamina', $startingstamina);
        $statement->bindParam('startingluck', $startingluck);
        $statement->bindParam('honour', $honour);
        $statement->bindParam('startingprovision', $startingprovision);
        $statement->execute();
    }

    public function getHero()
    {
        $em = $this->entityManager;
        $heroesRepository = $em->getRepository("App\Entity\Hero");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $hero = $heroesRepository->createQueryBuilder("h")
            ->select('MAX(h.id) as id')
            ->getQuery()
            ->getSingleScalarResult();

        return $hero;
    }

    public function getCreateHero()
    {
        return $this->createHero;
    }

}