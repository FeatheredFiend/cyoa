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

        $RAW_QUERY = "INSERT INTO adventure_paragraph(adventure_id, paragraph_id) VALUES (:adventure, :paragraph)";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('adventure', $adventure);
        $statement->bindParam('paragraph', $paragraph);
        $statement->execute();
    }
    

    public function getNextParagraph()
    {
        return $this->nextParagraph;
    }

}