<?php

namespace App\Repository;

use App\Entity\ParagraphDirectionEquipmentRequired;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ParagraphDirectionEquipmentRequired>
 *
 * @method ParagraphDirectionEquipmentRequired|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParagraphDirectionEquipmentRequired|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParagraphDirectionEquipmentRequired[]    findAll()
 * @method ParagraphDirectionEquipmentRequired[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphDirectionEquipmentRequiredRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParagraphDirectionEquipmentRequired::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParagraphDirectionEquipmentRequired $entity, bool $flush = true): void
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
    public function remove(ParagraphDirectionEquipmentRequired $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilderViewParagraph(?string $term, ?int $paragraph, ?int $paragraphdirection): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('pder','pd','p', 'e')
            ->from('App\Entity\ParagraphDirectionEquipmentRequired', 'pder')
            ->leftJoin('pder.paragraphdirection', 'pd')
            ->leftJoin('pd.paragraph', 'p')
            ->leftJoin('pder.equipment', 'e')
            ->andWhere('p.id = :paragraph')
            ->andWhere('pd.id = :paragraphdirection')
            ->setParameter('paragraph', $paragraph)               
            ->setParameter('paragraphdirection', $paragraphdirection)                        
            ->orderBy('pder.id', 'ASC');

        return $qb;

    } 


    // /**
    //  * @return ParagraphDirectionEquipmentRequired[] Returns an array of ParagraphDirectionEquipmentRequired objects
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
    public function findOneBySomeField($value): ?ParagraphDirectionEquipmentRequired
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
