<?php

namespace App\Repository;

use App\Entity\Battle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Battle>
 *
 * @method Battle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Battle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Battle[]    findAll()
 * @method Battle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BattleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Battle::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Battle $entity, bool $flush = true): void
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
    public function remove(Battle $entity, bool $flush = true): void
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
            ->select('b','e','ap')
            ->from('App\Entity\Battle', 'b')
            ->leftJoin('b.enemy', 'e')
            ->leftJoin('b.adventureparagraph', 'ap')
            ->orderBy('b.id', 'ASC');

        return $qb;

    }

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderPlay(?string $term, ?int $adventure, ?int $paragraph): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('b','e','ap','p')
            ->from('App\Entity\Battle', 'b')
            ->leftJoin('b.enemy', 'e')
            ->leftJoin('b.adventureparagraph', 'ap')
            ->leftJoin('ap.paragraph', 'p')
            ->andWhere('ap.adventure = :adventure')
            ->andWhere('p.id = :paragraph')
            ->setParameter('adventure', $adventure)
            ->setParameter('paragraph', $paragraph)
            ->setMaxResults(1)
            ->orderBy('b.id', 'DESC');

        return $qb;

    }

    // /**
    //  * @return Battle[] Returns an array of Battle objects
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
    public function findOneBySomeField($value): ?Battle
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
