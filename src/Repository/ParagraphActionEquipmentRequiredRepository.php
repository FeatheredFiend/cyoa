<?php

namespace App\Repository;

use App\Entity\ParagraphActionEquipmentRequired;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

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
