<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\GetHero;

class AddDay
{
    private $addDay;
    private $getHero;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $addDay, GetHero $getHero)
    {
        $this->addDay = $addDay;
        $this->getHero = $getHero;
        $this->entityManager = $entityManager;

    }
    
    public function gainDay($adventure, $day)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);

        $RAW_QUERY = "UPDATE hero SET day = day + :day WHERE hero_id = :hero";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('day', $day);
        $statement->execute();

    }   

    public function getAddDay()
    {
        return $this->addDay;
    }

}