<?php

namespace App\Repository;

use App\Entity\ParagraphSpell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphSpell>
 *
 * @method ParagraphSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphSpell[]    findAll()
 * @method ParagraphSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphSpellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphSpell::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphSpell $entity, bool $flush = true): void
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
    public function remove(ParagraphSpell $entity, bool $flush = true): void
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
            ->select('ps','p', 's', 'psc')
            ->from('App\Entity\ParagraphSpell', 'ps')
            ->leftJoin('ps.paragraph', 'p')
            ->leftJoin('ps.spell', 's')
            ->leftJoin('ps.paragraphspellcategory', 'psc')
            ->andWhere('p.id = :paragraph')
            ->setParameter('paragraph', $paragraph)
            ->orderBy('ps.id', 'ASC');

        return $qb;

    }        

    // /**
    //  * @return ParagraphSpell[] Returns an array of ParagraphSpell objects
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
    public function findOneBySomeField($value): ?ParagraphSpell
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
