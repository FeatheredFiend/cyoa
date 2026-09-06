<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\GetHero;

class UseSpell
{
    private $useSpell;
    private $getHero;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $useSpell, GetHero $getHero)
    {
        $this->useSpell = $useSpell;
        $this->getHero = $getHero;
        $this->entityManager = $entityManager;

    }
    
    public function useSpell($adventure, $spell)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);
        $spellId = $this->getSpell($spell);
        $spellEffects = $this->getSpellEffects($spell);

        foreach ($spellEffects as $spellEffect) {
            $magiceffectoperator = $spellEffect["magiceffectoperator"];
            $magiceffectattribute = $spellEffect["magiceffectattribute"];
            $magiceffectvalue = $spellEffect["magiceffectvalue"];

            if ($magiceffectoperator === "Add") {
                $this->applyEffect($hero, $magiceffectattribute, $magiceffectvalue);
            } else if ($magiceffectoperator === "Remove") {
                $this->applyEffect($hero, $magiceffectattribute, -$magiceffectvalue);
            }
        }


        $RAW_QUERY = "UPDATE hero_spell SET quantity = quantity - :quantity WHERE spell_id = :spell and hero_id = :hero";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('hero', $hero);
        $statement->bindValue('spell', $spellId);
        $statement->executeStatement();


        $this->removeSpell($adventure, $spell);

    }

    public function applyEffect($hero, $attribute, $value)
    {
        $em = $this->entityManager;

        $column = match ($attribute) {
            'Stamina' => 'stamina',
            'Skill' => 'skill',
            'Luck' => 'luck',
            default => null,
        };

        if ($column === null) {
            return;
        }

        $RAW_QUERY = "UPDATE hero SET {$column} = {$column} + :value WHERE hero.id = :hero";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('hero', $hero);
        $statement->bindValue('value', $value);
        $statement->executeStatement();
    }

    public function gainSpell($adventure, $spell)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);
        $spellId = $this->getSpell($spell);

        $RAW_QUERY = "INSERT INTO hero_spell(hero_id,spell_id) VALUES (:hero, :spell)";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('hero', $hero);
        $statement->bindValue('spell', $spellId);
        $statement->executeStatement();

    }   
    
    public function removeSpell($adventure, $spell)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);

        $RAW_QUERY = "DELETE FROM hero_spell WHERE hero_id = :hero AND spell_id = :spell";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('hero', $hero);
        $statement->bindValue('spell', $spell);
        $statement->executeStatement();

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
        $spellEffects = $spellsRepository->createQueryBuilder("s")
            ->select('meo.name as magiceffectoperator', 'mea.name as magiceffectattribute', 'me.magiceffectvalue as magiceffectvalue')
            ->leftJoin('s.magic','m')
            ->leftJoin('m.magicEffects','me')
            ->leftJoin('me.magiceffectoperator','meo')
            ->leftJoin('me.magiceffectattribute','mea')
            ->andWhere('s.name = :spell')
            ->setParameter('spell', $spell)
            ->getQuery()
            ->getArrayResult();

        return $spellEffects;
    }

    public function getUseSpell()
    {
        return $this->useSpell;
    }

}