<?php

namespace App\Repository;

use App\Entity\ParagraphEquipmentRequired;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParagraphEquipmentRequired>
 *
 * @method ParagraphEquipmentRequired|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphEquipmentRequired|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphEquipmentRequired[]    findAll()
 * @method ParagraphEquipmentRequired[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphEquipmentRequiredRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphEquipmentRequired::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphEquipmentRequired $entity, bool $flush = true): void
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
    public function remove(ParagraphEquipmentRequired $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ParagraphEquipmentRequired[] Returns an array of ParagraphEquipmentRequired objects
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
    public function findOneBySomeField($value): ?ParagraphEquipmentRequired
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
