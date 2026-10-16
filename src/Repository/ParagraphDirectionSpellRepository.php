<?php

namespace App\Repository;

use App\Entity\ParagraphDirectionSpell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphDirectionSpell>
 *
 * @method ParagraphDirectionSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphDirectionSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphDirectionSpell[]    findAll()
 * @method ParagraphDirectionSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphDirectionSpellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphDirectionSpell::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphDirectionSpell $entity, bool $flush = true): void
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
    public function remove(ParagraphDirectionSpell $entity, bool $flush = true): void
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
            ->select('pds','pd', 's', 'p')
            ->from('App\Entity\ParagraphDirectionSpell', 'pds')
            ->leftJoin('pds.paragraphdirection', 'pd')
            ->leftJoin('pds.spell', 's')
            ->leftJoin('pd.paragraph', 'p')
            ->andWhere('p.id = :paragraph')
            ->setParameter('paragraph', $paragraph)
            ->orderBy('pds.id', 'ASC');

        return $qb;

    }     

    // /**
    //  * @return ParagraphDirectionSpell[] Returns an array of ParagraphDirectionSpell objects
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
    public function findOneBySomeField($value): ?ParagraphDirectionSpell
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
