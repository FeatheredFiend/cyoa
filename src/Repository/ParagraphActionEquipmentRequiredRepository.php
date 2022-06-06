<?php

namespace App\Repository;

use App\Entity\ParagraphActionEquipmentRequired;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphActionEquipmentRequired>
 *
 * @method ParagraphActionEquipmentRequired|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphActionEquipmentRequired|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphActionEquipmentRequired[]    findAll()
 * @method ParagraphActionEquipmentRequired[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphActionEquipmentRequiredRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphActionEquipmentRequired::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphActionEquipmentRequired $entity, bool $flush = true): void
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
    public function remove(ParagraphActionEquipmentRequired $entity, bool $flush = true): void
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
            ->select('paer','pa', 'p', 'e')
            ->from('App\Entity\ParagraphActionEquipmentRequired', 'paer')
            ->leftJoin('paer.paragraphaction', 'pa')
            ->leftJoin('pa.paragraph', 'p')
            ->leftJoin('paer.equipment', 'e')
            ->andWhere('p.id = :paragraph')
            ->setParameter('paragraph', $paragraph)
            ->orderBy('paer.id', 'ASC');

        return $qb;

    }     

    // /**
    //  * @return ParagraphActionEquipmentRequired[] Returns an array of ParagraphActionEquipmentRequired objects
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
    public function findOneBySomeField($value): ?ParagraphActionEquipmentRequired
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
