<?php

namespace App\Repository;

use App\Entity\WC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WC|null find($id, $lockMode = null, $lockVersion = null)
 * @method WC|null findOneBy(array $criteria, array $orderBy = null)
 * @method WC[]    findAll()
 * @method WC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WCRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WC::class);
    }

    // /**
    //  * @return WC[] Returns an array of WC objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WC
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
