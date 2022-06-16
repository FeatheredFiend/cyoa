<?php

namespace App\Repository;

use App\Entity\MagicEffectAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<MagicEffectAttribute>
 *
 * @method MagicEffectAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagicEffectAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagicEffectAttribute[]    findAll()
 * @method MagicEffectAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagicEffectAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagicEffectAttribute::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MagicEffectAttribute $entity, bool $flush = true): void
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
    public function remove(MagicEffectAttribute $entity, bool $flush = true): void
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
            ->select('mea')
            ->from('App\Entity\MagicEffectAttribute', 'mea')
            ->orderBy('mea.id', 'ASC');

        return $qb;

    }      

    // /**
    //  * @return MagicEffectAttribute[] Returns an array of MagicEffectAttribute objects
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
    public function findOneBySomeField($value): ?MagicEffectAttribute
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
