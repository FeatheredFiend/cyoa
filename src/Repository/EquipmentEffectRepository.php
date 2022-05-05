<?php

namespace App\Repository;

use App\Entity\EquipmentEffect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<EquipmentEffect>
 *
 * @method EquipmentEffect|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipmentEffect|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipmentEffect[]    findAll()
 * @method EquipmentEffect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentEffectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipmentEffect::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EquipmentEffect $entity, bool $flush = true): void
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
    public function remove(EquipmentEffect $entity, bool $flush = true): void
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
            ->select('ee','e','eeo','eea')
            ->from('App\Entity\EquipmentEffect', 'ee')
            ->leftJoin('ee.equipment', 'e')
            ->leftJoin('ee.equipmenteffectoperator', 'eeo')
            ->leftJoin('ee.equipmenteffectattribute', 'eea')
            ->orderBy('ee.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return EquipmentEffect[] Returns an array of EquipmentEffect objects
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
    public function findOneBySomeField($value): ?EquipmentEffect
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
