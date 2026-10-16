<?php

namespace App\Repository;

use App\Entity\BattleCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<BattleCategory>
 *
 * @method BattleCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BattleCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BattleCategory[]    findAll()
 * @method BattleCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BattleCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BattleCategory::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(BattleCategory $entity, bool $flush = true): void
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
    public function remove(BattleCategory $entity, bool $flush = true): void
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
            ->select('bc')
            ->from('App\Entity\BattleCategory', 'bc')
            ->orderBy('bc.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return BattleCategory[] Returns an array of BattleCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BattleCategory
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
