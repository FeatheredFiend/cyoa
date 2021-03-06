<?php

namespace App\Repository;

use App\Entity\AdventureParagraph;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<AdventureParagraph>
 *
 * @method AdventureParagraph|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdventureParagraph|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdventureParagraph[]    findAll()
 * @method AdventureParagraph[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdventureParagraphRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdventureParagraph::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AdventureParagraph $entity, bool $flush = true): void
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
    public function remove(AdventureParagraph $entity, bool $flush = true): void
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
            ->select('ap','a')
            ->from('App\Entity\AdventureParagraph', 'ap')
            ->leftJoin('ap.adventure', 'a')
            ->orderBy('ap.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return AdventureParagraph[] Returns an array of AdventureParagraph objects
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
    public function findOneBySomeField($value): ?AdventureParagraph
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
