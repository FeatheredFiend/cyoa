<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\GetHero;

class UseEquipment
{
    private $useEquipment;
    private $getHero;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $useEquipment, GetHero $getHero)
    {
        $this->useEquipment = $useEquipment;
        $this->getHero = $getHero;
        $this->entityManager = $entityManager;

    }
    
    public function useEquipment($adventure, $equipment, $quantity)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);
        $equipmentId = $this->getEquipment($equipment);

        $RAW_QUERY = "UPDATE hero_equipment SET quantity = quantity - :quantity WHERE equipment_id = :equipment and hero_id = :hero";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('equipment', $equipmentId);
        $statement->bindParam('quantity', $quantity);
        $statement->execute();

        $count = $this->countEquipment($hero, $equipmentId);
        if ($count !== NULL) {
            if ($count['quantity'] < 1) {
                $this->removeEquipment($adventure, $equipmentId);
            }
        }

    }

    public function gainEquipment($adventure, $equipment, $quantity)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);
        $equipmentId = $this->getEquipment($equipment);

        $count = $this->countEquipment($hero, $equipmentId);
        if ($count !== NULL) {

            $RAW_QUERY = "UPDATE hero_equipment SET quantity = quantity + :quantity WHERE equipment_id = :equipment and hero_id = :hero";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->bindParam('hero', $hero);
            $statement->bindParam('equipment', $equipmentId);
            $statement->bindParam('quantity', $quantity);
            $statement->execute();

            } else {

            $RAW_QUERY = "INSERT INTO hero_equipment(hero_id,equipment_id,quantity) VALUES (:hero, :equipment, :quantity)";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->bindParam('hero', $hero);
            $statement->bindParam('equipment', $equipmentId);
            $statement->bindParam('quantity', $quantity);
            $statement->execute();

        }

    }    

    public function removeEquipment($adventure, $equipment)
    {
        $em = $this->entityManager;

        $hero = $this->getHero($adventure);

        $RAW_QUERY = "DELETE FROM hero_equipment WHERE hero_id = :hero AND equipment_id = :equipment";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindParam('hero', $hero);
        $statement->bindParam('equipment', $equipment);
        $statement->execute();

    }

    public function getEquipment($equipment)
    {
        $em = $this->entityManager;
        $equipmentsRepository = $em->getRepository("App\Entity\Equipment");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $equipment = $equipmentsRepository->createQueryBuilder("e")
            ->select('e.id')
            ->andWhere('e.name = :equipment')
            ->setParameter('equipment', $equipment)
            ->getQuery()
            ->getSingleScalarResult();

        return $equipment;
    }    

    public function countEquipment($hero, $equipment)
    {
        $em = $this->entityManager;
        $heroeequipmentsRepository = $em->getRepository("App\Entity\HeroEquipment");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $hero = $heroeequipmentsRepository->createQueryBuilder("he")
            ->select('he.quantity')
            ->andWhere('he.hero = :hero')
            ->andWhere('he.equipment = :equipment')
            ->setParameter('hero', $hero)
            ->setParameter('equipment', $equipment)   
            ->getQuery()
            ->getOneOrNullResult();

        return $hero;
    }

    public function getUseEquipment()
    {
        return $this->useEquipment;
    }

}