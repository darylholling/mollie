<?php

namespace App\Repository;

use App\Entity\Orderregel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Orderregel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orderregel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orderregel[]    findAll()
 * @method Orderregel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderregelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Orderregel::class);
    }

    // /**
    //  * @return Orderregel[] Returns an array of Orderregel objects
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
    public function findOneBySomeField($value): ?Orderregel
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
