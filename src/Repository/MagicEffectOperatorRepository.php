<?php

namespace App\Repository;

use App\Entity\MagicEffectOperator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MagicEffectOperator>
 *
 * @method MagicEffectOperator|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagicEffectOperator|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagicEffectOperator[]    findAll()
 * @method MagicEffectOperator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagicEffectOperatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagicEffectOperator::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MagicEffectOperator $entity, bool $flush = true): void
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
    public function remove(MagicEffectOperator $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return MagicEffectOperator[] Returns an array of MagicEffectOperator objects
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
    public function findOneBySomeField($value): ?MagicEffectOperator
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
