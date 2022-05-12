<?php

namespace App\Repository;

use App\Entity\ParagraphAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphAction>
 *
 * @method ParagraphAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphAction[]    findAll()
 * @method ParagraphAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphAction::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphAction $entity, bool $flush = true): void
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
    public function remove(ParagraphAction $entity, bool $flush = true): void
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
            ->select('pa','pac','pao','paa', 'pat')
            ->from('App\Entity\ParagraphAction', 'pa')
            ->leftJoin('pa.paragraphactioncategory', 'pac')
            ->leftJoin('pa.paragraphactionoperator', 'pao')
            ->leftJoin('pa.paragraphactionattribute', 'paa')
            ->leftJoin('pa.paragraphactiontarget', 'pat')
            ->orderBy('pa.id', 'ASC');

        return $qb;

    } 

        /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderViewParagraph(?string $term, ?int $id): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('pa','pac','pao','paa')
            ->from('App\Entity\ParagraphAction', 'pa')
            ->leftJoin('pa.paragraphactioncategory', 'pac')
            ->leftJoin('pa.paragraphactionoperator', 'pao')
            ->leftJoin('pa.paragraphactionattribute', 'paa')
            ->andWhere('pa.paragraph = :paragraph')
            ->setParameter('paragraph', $id)
            ->orderBy('pa.id', 'ASC');

        return $qb;

    } 
      
    // /**
    //  * @return ParagraphAction[] Returns an array of ParagraphAction objects
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
    public function findOneBySomeField($value): ?ParagraphAction
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
