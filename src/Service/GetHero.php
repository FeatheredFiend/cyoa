<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class GetHero
{
    private $getHero;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $getHero)
    {
        $this->getHero = $getHero;
        $this->entityManager = $entityManager;

    }

    public function getHero($adventure)
    {
        $em = $this->entityManager;
        $adventuresRepository = $em->getRepository("App\Entity\Adventure");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $hero = $adventuresRepository->createQueryBuilder("a")
            ->select('IDENTITY(a.hero)')
            ->andWhere('a.id = :adventure')
            ->setParameter('adventure', $adventure)
            ->getQuery()
            ->getSingleScalarResult();

        return $hero;
    } 

    public function getGetHero()
    {
        return $this->getHero;
    }

}