<?php

namespace App\Repository;

use App\Entity\Paragraph;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Paragraph>
 *
 * @method Paragraph|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paragraph|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paragraph[]    findAll()
 * @method Paragraph[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paragraph::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Paragraph $entity, bool $flush = true): void
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
    public function remove(Paragraph $entity, bool $flush = true): void
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
            ->select('p', 'g')
            ->from('App\Entity\Paragraph', 'p')
            ->leftJoin('p.gamebook', 'g')
            ->orderBy('p.id', 'ASC');

        return $qb;

    }

        /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderViewGamebook(?string $term, ?string $gamebook): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('p', 'g')
            ->from('App\Entity\Paragraph', 'p')
            ->leftJoin('p.gamebook', 'g')
            ->andWhere('g.name = :gamebook')
            ->setParameter('gamebook', $gamebook)
            ->orderBy('p.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return Paragraph[] Returns an array of Paragraph objects
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
    public function findOneBySomeField($value): ?Paragraph
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
