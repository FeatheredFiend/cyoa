<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\GetHero;

class BuyEquipment
{
    private $buyEquipment;
    private $getHero;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $buyEquipment, GetHero $getHero)
    {
        $this->buyEquipment = $buyEquipment;
        $this->getHero = $getHero;   
        $this->entityManager = $entityManager;

    }

    
    public function buyEquipment($adventure, $equipment, $quantity)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);

        $RAW_QUERY = "INSERT INTO hero_equipment(hero_id, equipment_id, quantity) VALUES (:hero, :equipment, :quantity)";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('equipment', $equipment);
        $statement->bindParam('quantity', $quantity);
        $statement->execute();
    }

    public function removeEquipment($merchantinventory)
    {
        $em = $this->entityManager;

        $RAW_QUERY = "DELETE FROM adventure_merchant_inventory WHERE merchantinventory_id = :merchantinventory";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('merchantinventory', $merchantinventory);
        $statement->execute();
    }

    public function getHeroTreasure($adventure)
    {
        $em = $this->entityManager;
        $heroesRepository = $em->getRepository("App\Entity\Adventure");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $treasure = $heroesRepository->createQueryBuilder("a")
            ->select('h.treasure')
            ->leftJoin('a.hero', 'h')
            ->andWhere('a.id = :adventure')
            ->setParameter('adventure', $adventure)
            ->getQuery()
            ->getSingleScalarResult();

        return $treasure;
    }

    public function getEquipmentCost($equipment)
    {
        $em = $this->entityManager;
        $heroesRepository = $em->getRepository("App\Entity\MerchantInventory");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $cost = $heroesRepository->createQueryBuilder("mi")
            ->select('mi.cost')
            ->andWhere('mi.equipment = :equipment')
            ->setParameter('equipment', $equipment)
            ->getQuery()
            ->getSingleScalarResult();

        return $cost;
    }

    public function getBuyEquipment()
    {
        return $this->buyEquipment;
    }

}