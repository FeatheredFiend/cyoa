<?php

namespace App\Repository;

use App\Entity\ParagraphActionSpell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphActionSpell>
 *
 * @method ParagraphActionSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphActionSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphActionSpell[]    findAll()
 * @method ParagraphActionSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphActionSpellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphActionSpell::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphActionSpell $entity, bool $flush = true): void
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
    public function remove(ParagraphActionSpell $entity, bool $flush = true): void
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
            ->select('pas','pa', 's', 'p')
            ->from('App\Entity\ParagraphActionSpell', 'pas')
            ->leftJoin('pas.paragraphaction', 'pa')
            ->leftJoin('pas.spell', 's')
            ->leftJoin('pa.paragraph', 'p')
            ->andWhere('p.id = :paragraph')
            ->setParameter('paragraph', $paragraph)
            ->orderBy('pas.id', 'ASC');

        return $qb;

    }      

    // /**
    //  * @return ParagraphActionSpell[] Returns an array of ParagraphActionSpell objects
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
    public function findOneBySomeField($value): ?ParagraphActionSpell
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
