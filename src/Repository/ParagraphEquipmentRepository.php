<?php

namespace App\Repository;

use App\Entity\ParagraphEquipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphEquipment>
 *
 * @method ParagraphEquipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphEquipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphEquipment[]    findAll()
 * @method ParagraphEquipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphEquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphEquipment::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphEquipment $entity, bool $flush = true): void
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
    public function remove(ParagraphEquipment $entity, bool $flush = true): void
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
            ->select('pe', 'p', 'e', 'pec')
            ->from('App\Entity\ParagraphEquipment', 'pe')
            ->leftJoin('pe.paragraph', 'p')
            ->leftJoin('pe.equipment', 'e')
            ->leftJoin('pe.paragraphequipmentcategory', 'pec')
            ->andWhere('p.id = :paragraph')
            ->setParameter('paragraph', $paragraph)
            ->orderBy('pe.id', 'ASC');

        return $qb;

    }    
    
    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderPlay(?string $term, ?int $adventure, ?int $paragraph): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('pe','p','ap','e','pec')
            ->from('App\Entity\ParagraphEquipment', 'pe')
            ->leftJoin('pe.paragraph', 'p')
            ->leftJoin('p.adventureParagraphs','ap')
            ->leftJoin('pe.equipment','e')
            ->leftJoin('pe.paragraphequipmentcategory', 'pec')
            ->andWhere('ap.adventure = :adventure')
            ->andWhere('p.id = :paragraph')
            ->setParameter('adventure', $adventure)
            ->setParameter('paragraph', $paragraph)
            ->orderBy('pe.id', 'ASC');

        return $qb;

    }        

    // /**
    //  * @return ParagraphEquipment[] Returns an array of ParagraphEquipment objects
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
    public function findOneBySomeField($value): ?ParagraphEquipment
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
