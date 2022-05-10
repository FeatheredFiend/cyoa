<?php

namespace App\Repository;

use App\Entity\EquipmentRequired;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<EquipmentRequired>
 *
 * @method EquipmentRequired|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipmentRequired|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipmentRequired[]    findAll()
 * @method EquipmentRequired[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentRequiredRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipmentRequired::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EquipmentRequired $entity, bool $flush = true): void
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
    public function remove(EquipmentRequired $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

        /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderView(?string $term, ?string $gamebook, string $paragraph): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('er', 'p', 'g')
            ->from('App\Entity\EquipmentRequired', 'er')
            ->leftJoin('er.paragraph', 'p')
            ->leftJoin('p.gamebook', 'g')
            ->andWhere('g.name = :gamebook')
            ->andWhere('p.number = :paragraph')
            ->setParameter('gamebook', $gamebook)
            ->setParameter('paragraph', $paragraph)
            ->orderBy('er.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return EquipmentRequired[] Returns an array of EquipmentRequired objects
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
    public function findOneBySomeField($value): ?EquipmentRequired
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
