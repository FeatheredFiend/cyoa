<?php

namespace App\Repository;

use App\Entity\ParagraphActionEnemy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphActionEnemy>
 *
 * @method ParagraphActionEnemy|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphActionEnemy|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphActionEnemy[]    findAll()
 * @method ParagraphActionEnemy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphActionEnemyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphActionEnemy::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphActionEnemy $entity, bool $flush = true): void
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
    public function remove(ParagraphActionEnemy $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderViewParagraph(?string $term, ?int $paragraph): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('pae','pa', 'p', 'e')
            ->from('App\Entity\ParagraphActionEnemy', 'pae')
            ->leftJoin('pae.paragraphaction', 'pa')
            ->leftJoin('pa.paragraph', 'p')
            ->leftJoin('pae.enemy', 'e')
            ->andWhere('p.id = :paragraph')
            ->setParameter('paragraph', $paragraph)
            ->orderBy('pae.id', 'ASC');

        return $qb;

    }       

    // /**
    //  * @return ParagraphActionEnemy[] Returns an array of ParagraphActionEnemy objects
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
    public function findOneBySomeField($value): ?ParagraphActionEnemy
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
