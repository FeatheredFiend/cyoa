<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ParagraphActionRun
{
    private $paragraphActionRun;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $paragraphActionRun)
    {
        $this->paragraphActionRun = $paragraphActionRun;
        $this->entityManager = $entityManager;

    }

    
    public function paragraphActionRun($adventure, $paragraph, $paragraphaction)
    {
        $em = $this->entityManager;
        $category = $this->getParagraphActionCategory($paragraphaction);
        $operator = $this->getParagraphActionOperator($paragraphaction);
        $attribute = $this->getParagraphActionAttribute($paragraphaction);
        $value = $this->getParagraphActionValue($paragraphaction);
        $target = $this->getParagraphActionTarget($paragraphaction);
        $hero = $this->getAdventureHero($adventure);

        if ($target == "Player") {
            if ($category == "Battle") {

            } else if ($category == "Attribute Change") {
                if ($operator == "Add"){
                    if ($attribute == "Stamina") {
                        $RAW_QUERY = "UPDATE hero SET stamina= stamina + :value WHERE hero.id = :hero";         
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('hero', $hero);
                        $statement->bindParam('value', $value);
                        $statement->execute();
                        
                    } else if ($attribute == "Skill") {
                        $RAW_QUERY = "UPDATE hero SET skill= skill + :value WHERE hero.id = :hero";       
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('hero', $hero);
                        $statement->bindParam('value', $value);
                        $statement->execute();
                    } else if ($attribute == "Luck") {
                        $RAW_QUERY = "UPDATE hero SET luck= luck + :value WHERE hero.id = :hero";         
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('hero', $hero);
                        $statement->bindParam('value', $value);
                        $statement->execute();
                    }
                } else {
                    if ($attribute == "Stamina") {
                        $RAW_QUERY = "UPDATE hero SET stamina= stamina - :value WHERE hero.id = :hero";         
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('hero', $hero);
                        $statement->bindParam('value', $value);
                        $statement->execute();
                        
                    } else if ($attribute == "Skill") {
                        $RAW_QUERY = "UPDATE hero SET skill= skill - :value WHERE hero.id = :hero";       
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('hero', $hero);
                        $statement->bindParam('value', $value);
                        $statement->execute();
                    } else if ($attribute == "Luck") {
                        $RAW_QUERY = "UPDATE hero SET luck= luck - :value WHERE hero.id = :hero";         
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('hero', $hero);
                        $statement->bindParam('value', $value);
                        $statement->execute();
                    }
                }

            } else if ($category == "Item Check") {
                $RAW_QUERY = "INSERT INTO hero_equipment(hero_id, equipment_id, quantity) VALUES (:hero, :equipment, :quantity)";         
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->bindParam('hero', $hero);
                $statement->bindParam('equipment', $equipment);
                $statement->bindParam('quantity', $value);
                $statement->execute();
            } else {

            }                
        } else {

        }

    }

    public function getAdventureHero($adventure)
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
    
    public function getParagraphActionCategory($paragraphaction)
    {
        $em = $this->entityManager;
        $paragraphactionsRepository = $em->getRepository("App\Entity\ParagraphAction");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $actionCategory = $paragraphactionsRepository->createQueryBuilder("pa")
            ->select('pac.name')
            ->leftJoin('pa.paragraphactioncategory', 'pac')
            ->andWhere('pa.id = :paragraphaction')
            ->setParameter('paragraphaction', $paragraphaction)
            ->getQuery()
            ->getSingleScalarResult();

        return $actionCategory;
    }

    public function getParagraphActionOperator($paragraphaction)
    {
        $em = $this->entityManager;
        $paragraphactionsRepository = $em->getRepository("App\Entity\ParagraphAction");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $actionOperator = $paragraphactionsRepository->createQueryBuilder("pa")
            ->select('pao.name')
            ->leftJoin('pa.paragraphactionoperator', 'pao')
            ->andWhere('pa.id = :paragraphaction')
            ->setParameter('paragraphaction', $paragraphaction)
            ->getQuery()
            ->getSingleScalarResult();

        return $actionOperator;
    }

    public function getParagraphActionAttribute($paragraphaction)
    {
        $em = $this->entityManager;
        $paragraphactionsRepository = $em->getRepository("App\Entity\ParagraphAction");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $actionAttribute = $paragraphactionsRepository->createQueryBuilder("pa")
            ->select('paa.name')
            ->leftJoin('pa.paragraphactionattribute', 'paa')
            ->andWhere('pa.id = :paragraphaction')
            ->setParameter('paragraphaction', $paragraphaction)
            ->getQuery()
            ->getSingleScalarResult();

        return $actionAttribute;
    }

    public function getParagraphActionValue($paragraphaction)
    {
        $em = $this->entityManager;
        $paragraphactionsRepository = $em->getRepository("App\Entity\ParagraphAction");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $actionValue = $paragraphactionsRepository->createQueryBuilder("pa")
            ->select('pa.actionvalue')
            ->andWhere('pa.id = :paragraphaction')
            ->setParameter('paragraphaction', $paragraphaction)
            ->getQuery()
            ->getSingleScalarResult();

        return $actionValue;
    }

    public function getParagraphActionTarget($paragraphaction)
    {
        $em = $this->entityManager;
        $paragraphactionsRepository = $em->getRepository("App\Entity\ParagraphAction");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $actionTarget = $paragraphactionsRepository->createQueryBuilder("pa")
            ->select('pat.name')
            ->leftJoin('pa.paragraphactiontarget', 'pat')            
            ->andWhere('pa.id = :paragraphaction')
            ->setParameter('paragraphaction', $paragraphaction)
            ->getQuery()
            ->getSingleScalarResult();

        return $actionTarget;
    }

    public function getParagraphActionRun()
    {
        return $this->paragraphActionRun;
    }

}