<?php

namespace App\Repository;

use App\Entity\ParagraphActionOperator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphActionOperator>
 *
 * @method ParagraphActionOperator|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphActionOperator|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphActionOperator[]    findAll()
 * @method ParagraphActionOperator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphActionOperatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphActionOperator::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphActionOperator $entity, bool $flush = true): void
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
    public function remove(ParagraphActionOperator $entity, bool $flush = true): void
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
            ->select('pao')
            ->from('App\Entity\ParagraphActionOperator', 'pao')
            ->orderBy('pao.id', 'ASC');

        return $qb;

    }   

    // /**
    //  * @return ParagraphActionOperator[] Returns an array of ParagraphActionOperator objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParagraphActionOperator
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
