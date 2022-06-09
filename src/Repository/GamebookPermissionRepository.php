<?php

namespace App\Repository;

use App\Entity\GamebookPermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<GamebookPermission>
 *
 * @method GamebookPermission|null find($id, $lockMode = null, $lockVersion = null)
 * @method GamebookPermission|null findOneBy(array $criteria, array $orderBy = null)
 * @method GamebookPermission[]    findAll()
 * @method GamebookPermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamebookPermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GamebookPermission::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(GamebookPermission $entity, bool $flush = true): void
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
    public function remove(GamebookPermission $entity, bool $flush = true): void
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
            ->select('gp','g','u')
            ->from('App\Entity\GamebookPermission', 'gp')
            ->leftJoin('gp.gamebook', 'g')
            ->leftJoin('gp.user', 'u')
            ->orderBy('gp.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return GamebookPermission[] Returns an array of GamebookPermission objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GamebookPermission
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
