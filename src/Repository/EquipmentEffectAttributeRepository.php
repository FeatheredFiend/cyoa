<?php

namespace App\Repository;

use App\Entity\EquipmentEffectAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<EquipmentEffectAttribute>
 *
 * @method EquipmentEffectAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipmentEffectAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipmentEffectAttribute[]    findAll()
 * @method EquipmentEffectAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentEffectAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipmentEffectAttribute::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EquipmentEffectAttribute $entity, bool $flush = true): void
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
    public function remove(EquipmentEffectAttribute $entity, bool $flush = true): void
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
            ->select('eea')
            ->from('App\Entity\EquipmentEffectAttribute', 'eea')
            ->orderBy('eea.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return EquipmentEffectAttribute[] Returns an array of EquipmentEffectAttribute objects
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
    public function findOneBySomeField($value): ?EquipmentEffectAttribute
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
