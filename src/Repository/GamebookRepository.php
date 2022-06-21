<?php

namespace App\Repository;

use App\Entity\Gamebook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Gamebook>
 *
 * @method Gamebook|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gamebook|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gamebook[]    findAll()
 * @method Gamebook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamebookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gamebook::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Gamebook $entity, bool $flush = true): void
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
    public function remove(Gamebook $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderView(?string $term, ?int $user): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('g', 'gp', 'u')
            ->from('App\Entity\Gamebook', 'g')
            ->leftJoin('g.gamebookPermissions', 'gp')
            ->leftJoin('gp.user', 'u')
            ->andWhere('u.id = :user')
            ->orWhere('g.license = :free')   
            ->setParameter('user', $user)
            ->setParameter('free', "FREE")
            ->orderBy('g.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return Gamebook[] Returns an array of Gamebook objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gamebook
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
