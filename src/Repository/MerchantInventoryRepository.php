<?php

namespace App\Repository;

use App\Entity\MerchantInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<MerchantInventory>
 *
 * @method MerchantInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MerchantInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MerchantInventory[]    findAll()
 * @method MerchantInventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchantInventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MerchantInventory::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MerchantInventory $entity, bool $flush = true): void
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
    public function remove(MerchantInventory $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderView(?string $term): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('mi','m', 'e')
            ->from('App\Entity\MerchantInventory', 'mi')
            ->leftJoin('mi.merchant', 'm')
            ->leftJoin('mi.equipment', 'e')
            ->orderBy('mi.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return MerchantInventory[] Returns an array of MerchantInventory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MerchantInventory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
