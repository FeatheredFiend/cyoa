<?php

namespace App\Repository;

use App\Entity\AdventureMerchantInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdventureMerchantInventory>
 *
 * @method AdventureMerchantInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdventureMerchantInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdventureMerchantInventory[]    findAll()
 * @method AdventureMerchantInventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdventureMerchantInventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdventureMerchantInventory::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AdventureMerchantInventory $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(AdventureMerchantInventory $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return AdventureMerchantInventory[] Returns an array of AdventureMerchantInventory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdventureMerchantInventory
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
