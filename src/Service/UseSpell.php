<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class UseSpell
{
    private $useSpell;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $useSpell)
    {
        $this->useSpell = $useSpell;
        $this->entityManager = $entityManager;

    }
    
    public function useSpell($adventure, $spell)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);
        $spellId = $this->getSpell($spell);

        $RAW_QUERY = "UPDATE hero_spell SET quantity = quantity - :quantity WHERE spell_id = :spell and hero_id = :hero";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('spell', $spellId);
        $statement->execute();

        $count = $this->countSpell($hero, $spellId);
        if ($count !== NULL) {
            if ($count['quantity'] < 1) {
                $this->removeSpell($adventure, $spellId);
            }
        }

    }

    public function gainSpell($adventure, $spell, $quantity)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);
        $spellId = $this->getSpell($spell);

        $count = $this->countSpell($hero, $spellId);
        if ($count !== NULL) {

            $RAW_QUERY = "UPDATE hero_spell SET quantity = quantity + :quantity WHERE spell_id = :spell and hero_id = :hero";         
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->bindParam('hero', $hero);
            $statement->bindParam('spell', $spellId);
            $statement->bindParam('quantity', $quantity);
            $statement->execute();

            } else {

            $RAW_QUERY = "INSERT INTO hero_spell(hero_id,spell_id,quantity) VALUES (:hero, :spell, :quantity)";         
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->bindParam('hero', $hero);
            $statement->bindParam('spell', $spellId);
            $statement->bindParam('quantity', $quantity);
            $statement->execute();

        }

    }    

    public function removeSpell($adventure, $spell)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);

        $RAW_QUERY = "DELETE FROM hero_spell WHERE hero_id = :hero AND spell_id = :spell";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('spell', $spell);
        $statement->execute();
        var_dump($RAW_QUERY);
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

    public function getSpell($spell)
    {
        $em = $this->entityManager;
        $spellsRepository = $em->getRepository("App\Entity\Spell");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $spell = $spellsRepository->createQueryBuilder("e")
            ->select('e.id')
            ->andWhere('e.name = :spell')
            ->setParameter('spell', $spell)
            ->getQuery()
            ->getSingleScalarResult();

        return $spell;
    }    

    public function countSpell($hero, $spell)
    {
        $em = $this->entityManager;
        $heroespellsRepository = $em->getRepository("App\Entity\HeroSpell");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $hero = $heroespellsRepository->createQueryBuilder("he")
            ->select('he.quantity')
            ->andWhere('he.hero = :hero')
            ->andWhere('he.spell = :spell')
            ->setParameter('hero', $hero)
            ->setParameter('spell', $spell)            
            ->getQuery()
            ->getOneOrNullResult();

        return $hero;
    }

    public function getUseSpell()
    {
        return $this->useSpell;
    }

}