<?php

namespace App\Repository;

use App\Entity\EquipmentEffectOperator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<EquipmentEffectOperator>
 *
 * @method EquipmentEffectOperator|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipmentEffectOperator|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipmentEffectOperator[]    findAll()
 * @method EquipmentEffectOperator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentEffectOperatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipmentEffectOperator::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EquipmentEffectOperator $entity, bool $flush = true): void
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
    public function remove(EquipmentEffectOperator $entity, bool $flush = true): void
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
            ->select('eeo')
            ->from('App\Entity\EquipmentEffectOperator', 'eeo')
            ->orderBy('eeo.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return EquipmentEffectOperator[] Returns an array of EquipmentEffectOperator objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EquipmentEffectOperator
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
