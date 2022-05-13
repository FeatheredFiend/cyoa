<?php

namespace App\Repository;

use App\Entity\Adventure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Adventure>
 *
 * @method Adventure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adventure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adventure[]    findAll()
 * @method Adventure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdventureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adventure::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Adventure $entity, bool $flush = true): void
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
    public function remove(Adventure $entity, bool $flush = true): void
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
            ->select('a','g','h','u', 'ap','p')
            ->from('App\Entity\Adventure', 'a')
            ->leftJoin('a.gamebook', 'g')
            ->leftJoin('a.hero', 'h')
            ->leftJoin('a.user', 'u')
            ->leftJoin('a.adventureParagraphs', 'ap')
            ->leftJoin('ap.paragraph', 'p')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('a.id', 'ASC');

        return $qb;

    }

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderHeroStats(?string $term, ?int $adventure): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('a','g','u','h','he')
            ->from('App\Entity\Adventure', 'a')
            ->leftJoin('a.gamebook', 'g')
            ->leftJoin('a.hero', 'h')
            ->leftJoin('h.heroEquipment','he')
            ->leftJoin('a.user', 'u')
            ->andWhere('a.id = :adventure')
            ->setParameter('adventure', $adventure)
            ->orderBy('a.id', 'ASC');

        return $qb;

    }







    // /**
    //  * @return Adventure[] Returns an array of Adventure objects
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
    public function findOneBySomeField($value): ?Adventure
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
