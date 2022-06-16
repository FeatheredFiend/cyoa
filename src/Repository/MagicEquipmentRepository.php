<?php

namespace App\Repository;

use App\Entity\MagicEquipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<MagicEquipment>
 *
 * @method MagicEquipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagicEquipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagicEquipment[]    findAll()
 * @method MagicEquipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagicEquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagicEquipment::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MagicEquipment $entity, bool $flush = true): void
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
    public function remove(MagicEquipment $entity, bool $flush = true): void
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
            ->select('me','m','e')
            ->from('App\Entity\MagicEquipment', 'me')
            ->leftJoin('me.magic','m')
            ->leftJoin('me.equipment','e')
            ->orderBy('me.id', 'ASC');

        return $qb;

    } 


    // /**
    //  * @return MagicEquipment[] Returns an array of MagicEquipment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MagicEquipment
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
