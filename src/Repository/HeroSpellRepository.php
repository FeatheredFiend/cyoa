<?php

namespace App\Repository;

use App\Entity\HeroSpell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<HeroSpell>
 *
 * @method HeroSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeroSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeroSpell[]    findAll()
 * @method HeroSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeroSpellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeroSpell::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(HeroSpell $entity, bool $flush = true): void
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
    public function remove(HeroSpell $entity, bool $flush = true): void
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
            ->select('hs','h','s')
            ->from('App\Entity\HeroSpell', 'hs')
            ->leftJoin('hs.hero','h')
            ->leftJoin('hs.spell','s')
            ->andWhere('h.name = :hero')
            ->setParameter('hero', $hero)
            ->orderBy('hs.id', 'ASC');

        return $qb;

    }   

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderPlay(?string $term, ?int $adventure): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('hs','s','h','a')
            ->from('App\Entity\HeroSpell', 'hs')
            ->leftJoin('hs.spell', 's')
            ->leftJoin('hs.hero', 'h')
            ->leftJoin('h.adventure', 'a')
            ->andWhere('a.id = :adventure')
            ->setParameter('adventure', $adventure)
            ->orderBy('hs.id', 'ASC');

        return $qb;

    }    
    
    // /**
    //  * @return HeroSpell[] Returns an array of HeroSpell objects
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
    public function findOneBySomeField($value): ?HeroSpell
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
