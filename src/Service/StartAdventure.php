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

    
    public function startAdventure($adventure,$paragraph)
    {
        $em = $this->entityManager;

        $RAW_QUERY = "INSERT INTO adventure_paragraph(adventure_id, paragraph_id) VALUES (:adventure, :paragraph)";         
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
    

    public function getStartAdventure()
    {
        return $this->startAdventure;
    }

}