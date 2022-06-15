<?php

namespace App\Repository;

use App\Entity\MagicEffect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MagicEffect>
 *
 * @method MagicEffect|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagicEffect|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagicEffect[]    findAll()
 * @method MagicEffect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagicEffectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagicEffect::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MagicEffect $entity, bool $flush = true): void
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
    public function remove(MagicEffect $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return MagicEffect[] Returns an array of MagicEffect objects
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
    public function findOneBySomeField($value): ?MagicEffect
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
