<?php

namespace App\Repository;

use App\Entity\ParagraphSpellCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphSpellCategory>
 *
 * @method ParagraphSpellCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphSpellCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphSpellCategory[]    findAll()
 * @method ParagraphSpellCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphSpellCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphSpellCategory::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphSpellCategory $entity, bool $flush = true): void
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
    public function remove(ParagraphSpellCategory $entity, bool $flush = true): void
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
            ->select('psc')
            ->from('App\Entity\ParagraphSpellCategory', 'psc')
            ->orderBy('psc.id', 'ASC');

        return $qb;

    }    

    // /**
    //  * @return ParagraphSpellCategory[] Returns an array of ParagraphSpellCategory objects
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
    public function findOneBySomeField($value): ?ParagraphSpellCategory
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
