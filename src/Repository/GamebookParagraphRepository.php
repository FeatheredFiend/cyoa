<?php

namespace App\Repository;

use App\Entity\GamebookParagraph;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<GamebookParagraph>
 *
 * @method GamebookParagraph|null find($id, $lockMode = null, $lockVersion = null)
 * @method GamebookParagraph|null findOneBy(array $criteria, array $orderBy = null)
 * @method GamebookParagraph[]    findAll()
 * @method GamebookParagraph[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamebookParagraphRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GamebookParagraph::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(GamebookParagraph $entity, bool $flush = true): void
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
    public function remove(GamebookParagraph $entity, bool $flush = true): void
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
            ->select('gp', 'g', 'p')
            ->from('App\Entity\GamebookParagraph', 'gp')
            ->leftJoin('gp.gamebook', 'g')
            ->leftJoin('gp.paragraph', 'p')
            ->orderBy('gp.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return GamebookParagraph[] Returns an array of GamebookParagraph objects
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
    public function findOneBySomeField($value): ?GamebookParagraph
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
