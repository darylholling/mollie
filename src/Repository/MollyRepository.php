<?php

namespace App\Repository;

use App\Entity\Molly;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Molly|null find($id, $lockMode = null, $lockVersion = null)
 * @method Molly|null findOneBy(array $criteria, array $orderBy = null)
 * @method Molly[]    findAll()
 * @method Molly[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MollyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Molly::class);
    }

    // /**
    //  * @return Molly[] Returns an array of Molly objects
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
    public function findOneBySomeField($value): ?Molly
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
