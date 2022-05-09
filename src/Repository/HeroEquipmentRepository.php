<?php

namespace App\Repository;

use App\Entity\HeroEquipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<HeroEquipment>
 *
 * @method HeroEquipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeroEquipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeroEquipment[]    findAll()
 * @method HeroEquipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeroEquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeroEquipment::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(HeroEquipment $entity, bool $flush = true): void
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
    public function remove(HeroEquipment $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderView(?string $term, ?string $hero): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('he','e','h')
            ->from('App\Entity\HeroEquipment', 'he')
            ->leftJoin('he.equipment', 'e')
            ->leftJoin('he.hero', 'h')
            ->andWhere('h.name = :hero')
            ->setParameter('hero', $hero)
            ->orderBy('he.id', 'ASC');

        return $qb;

    }

    // /**
    //  * @return HeroEquipment[] Returns an array of HeroEquipment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HeroEquipment
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
