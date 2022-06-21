<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class NextParagraph
{
    private $nextParagraph;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $nextParagraph)
    {
        $this->nextParagraph = $nextParagraph;
        $this->entityManager = $entityManager;

    }

    
    public function nextParagraph($adventure,$paragraph)
    {
        $em = $this->entityManager;

        $gamebook = $this->getAdventureGamebook($adventure);
        $paragraphId = $this->getGamebookParagraph($gamebook, $paragraph);
        $this->adventureProgress($adventure, $paragraphId);

        $RAW_QUERY = "INSERT INTO adventure_paragraph(adventure_id, paragraph_id) VALUES (:adventure, :paragraph)";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('adventure', $adventure);
        $statement->bindParam('paragraph', $paragraphId);
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

    public function getGamebookParagraph($gamebook, $paragraph)
    {
        $em = $this->entityManager;
        $gamebooksRepository = $em->getRepository("App\Entity\Gamebook");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $paragraph = $gamebooksRepository->createQueryBuilder("g")
            ->select('p.id')
            ->leftJoin('g.paragraphs', 'p')
            ->andWhere('g.id = :gamebook')
            ->andWhere('p.number = :paragraph')
            ->setParameter('gamebook', $gamebook)
            ->setParameter('paragraph', $paragraph)
            ->getQuery()
            ->getSingleScalarResult();

        return $paragraph;
    }    
    

    public function getNextParagraph()
    {
        return $this->nextParagraph;
    }

}