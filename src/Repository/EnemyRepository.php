<?php

namespace App\Repository;

use App\Entity\Enemy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Enemy>
 *
 * @method Enemy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enemy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enemy[]    findAll()
 * @method Enemy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnemyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enemy::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Enemy $entity, bool $flush = true): void
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
    public function remove(Enemy $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
        
    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderView(?string $term, ?int $paragraph): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('e','bc','p')
            ->from('App\Entity\Enemy', 'e')
            ->leftJoin('e.battlecategory', 'bc')
            ->leftJoin('e.paragraph', 'p')
            ->andWhere('e.paragraph = :paragraph')
            ->setParameter('paragraph', $paragraph)            
            ->orderBy('e.id', 'ASC');

        return $qb;

    }

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderPlay(?string $term, ?int $adventure, ?int $paragraph): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('e','bc','p', 'ap')
            ->from('App\Entity\Enemy', 'e')
            ->leftJoin('e.battlecategory', 'bc')
            ->leftJoin('e.paragraph', 'p')
            ->leftJoin('p.adventureParagraphs', 'ap')
            ->andWhere('ap.adventure = :adventure')
            ->andWhere('p.id = :paragraph')
            ->setParameter('adventure', $adventure)
            ->setParameter('paragraph', $paragraph)
            ->orderBy('e.id', 'ASC');

        return $qb;

    }    

    // /**
    //  * @return Enemy[] Returns an array of Enemy objects
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
    public function findOneBySomeField($value): ?Enemy
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
