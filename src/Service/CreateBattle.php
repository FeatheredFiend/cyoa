<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class CreateBattle
{
    private $createBattle;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $createBattle)
    {
        $this->createBattle = $createBattle;
        $this->entityManager = $entityManager;

    }

    
    public function createBattle($adventure, $paragraph, $enemy, $luck)
    {
        $em = $this->entityManager;

        $playerskill = $this->getHeroSkill($adventure) + rand(2,12);
        $playerstamina = $this->getHeroStamina($adventure);
        $enemyskill = $this->getEnemySkill($enemy) + rand(2,12);
        $enemystamina = $this->getEnemyStamina($enemy);        
        $round = 1;
        $paragraph = $this->getAdventureParagraph($adventure, $paragraph); 
        $playerluck = $this->getHeroLuck($adventure);

        if ($playerskill == $enemyskill) {
        } elseif ($playerskill > $enemyskill) {
            if ($luck == 1) {
                $testluck = rand(2,12);
                if ($testluck <= $playerluck) {
                    $enemystamina = $enemystamina - 4;
                } else {
                    $enemystamina = $enemystamina - 1;
                }     
                $this->reduceLuck($adventure);          
            } else {
                $enemystamina = $enemystamina - 2;
            }
        } elseif ($playerskill < $enemyskill) {
            if ($luck == 1) {
                $testluck = rand(2,12);
                if ($testluck <= $playerluck) {
                    $staminaLoss = 4;
                    $playerstamina = $playerstamina - 4;
                } else {
                    $staminaLoss = 1;
                    $playerstamina = $playerstamina - 1;
                }
                $this->reduceLuck($adventure);
            } else {
                $staminaLoss = 2;
                $playerstamina = $playerstamina - 2;
            }
            $this->reduceStamina($staminaLoss, $adventure);
        }

        $RAW_QUERY = "INSERT INTO battle(enemy_id, round, playerstamina, playerskill, enemystamina, enemyskill, adventureparagraph_id) VALUES (:enemy, :round, :playerstamina, :playerskill, :enemystamina, :enemyskill, :adventureparagraph)";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('enemy', $enemy);
        $statement->bindParam('round', $round);
        $statement->bindParam('playerskill', $playerskill);
        $statement->bindParam('playerstamina', $playerstamina);
        $statement->bindParam('enemyskill', $enemyskill);
        $statement->bindParam('enemystamina', $enemystamina);        
        $statement->bindParam('adventureparagraph', $paragraph); 
        $statement->execute();
    }

    public function reduceLuck($adventure)
    {
        $em = $this->entityManager;
        $RAW_QUERY = "UPDATE hero LEFT JOIN adventure ON hero.id = adventure.hero_id SET luck = luck - 1 WHERE adventure.id = :adventure";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('adventure', $adventure);
        $statement->execute();
    }

    public function reduceStamina($removeStamina, $adventure)
    {
        $em = $this->entityManager;
        $RAW_QUERY = "UPDATE hero LEFT JOIN adventure ON hero.id = adventure.hero_id SET stamina = stamina - :removeStamina WHERE adventure.id = :adventure";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('removeStamina', $removeStamina);
        $statement->bindParam('adventure', $adventure);
        $statement->execute();
    }

    public function getHeroStamina($adventure)
    {
        $em = $this->entityManager;
        $heroesRepository = $em->getRepository("App\Entity\Hero");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $hero = $heroesRepository->createQueryBuilder("h")
            ->select('h.stamina')
            ->leftJoin('h.adventure', 'a')
            ->andWhere('a.id = :adventure')
            ->setParameter('adventure', $adventure)
            ->getQuery()
            ->getSingleScalarResult();

        return $hero;
    }

    public function getHeroSkill($adventure)
    {
        $em = $this->entityManager;
        $heroesRepository = $em->getRepository("App\Entity\Hero");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $hero = $heroesRepository->createQueryBuilder("h")
            ->select('h.skill')
            ->leftJoin('h.adventure', 'a')
            ->andWhere('a.id = :adventure')
            ->setParameter('adventure', $adventure)
            ->getQuery()
            ->getSingleScalarResult();

        return $hero;
    }

    public function getHeroLuck($adventure)
    {
        $em = $this->entityManager;
        $heroesRepository = $em->getRepository("App\Entity\Hero");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $hero = $heroesRepository->createQueryBuilder("h")
            ->select('h.luck')
            ->leftJoin('h.adventure', 'a')
            ->andWhere('a.id = :adventure')
            ->setParameter('adventure', $adventure)
            ->getQuery()
            ->getSingleScalarResult();

        return $hero;
    }    

    public function getEnemyStamina($enemy)
    {
        $em = $this->entityManager;
        $enemiesRepository = $em->getRepository("App\Entity\Enemy");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $enemy = $enemiesRepository->createQueryBuilder("e")
            ->select('e.stamina')
            ->andWhere('e.id = :enemy')
            ->setParameter('enemy', $enemy)
            ->getQuery()
            ->getSingleScalarResult();

        return $enemy;
    }

    public function getEnemySkill($enemy)
    {
        $em = $this->entityManager;
        $enemiesRepository = $em->getRepository("App\Entity\Enemy");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $enemy = $enemiesRepository->createQueryBuilder("e")
            ->select('e.skill')
            ->andWhere('e.id = :enemy')
            ->setParameter('enemy', $enemy)
            ->getQuery()
            ->getSingleScalarResult();

        return $enemy;
    }

    public function getAdventureParagraph($adventure, $paragraph)
    {
        $em = $this->entityManager;
        $adventureparagraphRepository = $em->getRepository("App\Entity\AdventureParagraph");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $enemy = $adventureparagraphRepository->createQueryBuilder("ap")
            ->select('ap.id')
            ->andWhere('ap.adventure = :adventure')
            ->andWhere('ap.paragraph = :paragraph')
            ->setParameter('adventure', $adventure)
            ->setParameter('paragraph', $paragraph)
            ->getQuery()
            ->getSingleScalarResult();

        return $enemy;
    }

    public function getCreateBattle()
    {
        return $this->createBattle;
    }

}