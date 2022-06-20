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
        $spellEffects = $this->getSpellEffects($spell);


        $RAW_QUERY = "UPDATE hero_spell SET quantity = quantity - :quantity WHERE spell_id = :spell and hero_id = :hero";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('spell', $spellId);
        $statement->execute();


        $this->removeSpell($adventure, $spell);

    }

    public function gainSpell($adventure, $spell)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);
        $spellId = $this->getSpell($spell);

        $RAW_QUERY = "INSERT INTO hero_spell(hero_id,spell_id) VALUES (:hero, :spell)";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('spell', $spellId);
        $statement->execute();

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
    
    public function getSpellEffects($spell)
    {
        $em = $this->entityManager;
        $spellsRepository = $em->getRepository("App\Entity\Spell");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $spell = $spellsRepository->createQueryBuilder("s")
            ->select('s.id as spell', 'meo.id as magiceffectoperator', 'mea.id as magiceffectattribute','me.magiceffectvalue as magiceffectvalue')
            ->leftJoin('s.magic','m')
            ->leftJoin('m.magicEffect','me')
            ->leftJoin('me.magiceffectoperator','meo')
            ->leftJoin('me.magiceffectattribute','mea')
            ->andWhere('s.name = :spell')
            ->setParameter('spell', $spell)
            ->getQuery()
            ->getSingleScalarResult();

        return $spell;
    }       

    public function getUseSpell()
    {
        return $this->useSpell;
    }

}