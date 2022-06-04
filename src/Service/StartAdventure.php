<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StartAdventure
{
    private $startAdventure;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $startAdventure)
    {
        $this->startAdventure = $startAdventure;
        $this->entityManager = $entityManager;

    }

    
    public function startAdventure($adventure)
    {
        $em = $this->entityManager;

        $gamebook = $this->getAdventureGamebook($adventure);
        $paragraph = $this->getGamebookParagraph($gamebook);
        $this->setProgressParagraph($adventure, $paragraph);

        $RAW_QUERY = "INSERT INTO adventure_paragraph(adventure_id, paragraph_id) VALUES (:adventure, :paragraph)";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('adventure', $adventure);
        $statement->bindParam('paragraph', $paragraph);
        $statement->execute();
    }

    public function setProgressParagraph($adventure, $paragraph)
    {
        $em = $this->entityManager;

        $RAW_QUERY = "UPDATE adventure SET progressparagraph = :paragraph WHERE id = :adventure";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('adventure', $adventure);
        $statement->bindParam('paragraph', $paragraph);
        $statement->execute();
    }    

    public function adventureProgress($adventure,$paragraph)
    {
        $em = $this->entityManager;

        $RAW_QUERY = "UPDATE adventure SET progressparagraph = :paragraph WHERE id = :adventure";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('adventure', $adventure);
        $statement->bindParam('paragraph', $paragraph);
        $statement->execute();
    }

    
    public function getAdventureGamebook($adventure)
    {
        $em = $this->entityManager;
        $adventuresRepository = $em->getRepository("App\Entity\Adventure");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $gamebook = $adventuresRepository->createQueryBuilder("a")
            ->select('IDENTITY(a.gamebook)')
            ->andWhere('a.id = :adventure')
            ->setParameter('adventure', $adventure)
            ->getQuery()
            ->getSingleScalarResult();

        return $gamebook;
    }

    public function getGamebookParagraph($gamebook)
    {
        $em = $this->entityManager;
        $gamebooksRepository = $em->getRepository("App\Entity\Gamebook");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $paragraph = $gamebooksRepository->createQueryBuilder("g")
            ->select('MIN(p.id)')
            ->leftJoin('g.paragraphs', 'p')
            ->andWhere('g.id = :gamebook')
            ->setParameter('gamebook', $gamebook)
            ->getQuery()
            ->getSingleScalarResult();

        return $paragraph;
    }    
    

    public function getStartAdventure()
    {
        return $this->startAdventure;
    }

}