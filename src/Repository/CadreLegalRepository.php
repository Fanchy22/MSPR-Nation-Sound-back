<?php

namespace App\Repository;

use App\Entity\CadreLegal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CadreLegal|null find($id, $lockMode = null, $lockVersion = null)
 * @method CadreLegal|null findOneBy(array $criteria, array $orderBy = null)
 * @method CadreLegal[]    findAll()
 * @method CadreLegal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CadreLegalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CadreLegal::class);
    }

    // /**
    //  * @return CadreLegal[] Returns an array of CadreLegal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CadreLegal
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
