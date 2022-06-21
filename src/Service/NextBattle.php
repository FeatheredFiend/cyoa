<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class NextBattle
{
    private $nextBattle;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $nextBattle)
    {
        $this->nextBattle = $nextBattle;
        $this->entityManager = $entityManager;

    }

    
    public function nextBattle($adventure, $battle, $luck)
    {
        $em = $this->entityManager;

        $enemy = $this->getBattleEnemy($battle);
        $round = $this->getBattleRound($battle)+1;
        $playerstamina = $this->getBattleHeroStamina($battle);
        $playerskill = $this->getHeroSkill($adventure) + rand(2,12);
        $enemyskill = $this->getEnemySkill($enemy) + rand(2,12);
        $enemystamina = $this->getBattleEnemyStamina($battle);
        $playerluck = $this->getHeroLuck($adventure);
        $adventureparagraph = $this->getBattleParagraph($battle);

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
        $statement->bindParam('adventureparagraph', $adventureparagraph);
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

    public function getBattleEnemy($battle)
    {
        $em = $this->entityManager;
        $battlesRepository = $em->getRepository("App\Entity\Battle");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $battle = $battlesRepository->createQueryBuilder("b")
            ->select('IDENTITY(b.enemy)')
            ->leftJoin('b.enemy', 'e')
            ->andWhere('b.id = :battle')
            ->setParameter('battle', $battle)
            ->getQuery()
            ->getSingleScalarResult();

        return $battle;
    }

    public function getBattleRound($battle)
    {
        $em = $this->entityManager;
        $battlesRepository = $em->getRepository("App\Entity\Battle");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $battle = $battlesRepository->createQueryBuilder("b")
            ->select('b.round')
            ->andWhere('b.id = :battle')
            ->setParameter('battle', $battle)
            ->getQuery()
            ->getSingleScalarResult();

        return $battle;
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

    public function getBattleHeroStamina($battle)
    {
        $em = $this->entityManager;
        $battlesRepository = $em->getRepository("App\Entity\Battle");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $battle = $battlesRepository->createQueryBuilder("b")
            ->select('b.playerstamina')
            ->andWhere('b.id = :battle')
            ->setParameter('battle', $battle)
            ->getQuery()
            ->getSingleScalarResult();

        return $battle;
    }  
    
    public function getBattleEnemyStamina($battle)
    {
        $em = $this->entityManager;
        $battlesRepository = $em->getRepository("App\Entity\Battle");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $battle = $battlesRepository->createQueryBuilder("b")
            ->select('b.enemystamina')
            ->andWhere('b.id = :battle')
            ->setParameter('battle', $battle)
            ->getQuery()
            ->getSingleScalarResult();

        return $battle;
    }    

    public function getBattleParagraph($battle)
    {
        $em = $this->entityManager;
        $battlesRepository = $em->getRepository("App\Entity\Battle");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $battle = $battlesRepository->createQueryBuilder("b")
            ->select('IDENTITY(b.adventureparagraph)')
            ->andWhere('b.id = :battle')
            ->setParameter('battle', $battle)
            ->getQuery()
            ->getSingleScalarResult();

        return $battle;
    }    


    public function getNextBattle()
    {
        return $this->nextBattle;
    }

}