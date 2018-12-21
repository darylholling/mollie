<?php

namespace App\Repository;

use App\Entity\Betaling;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Betaling|null find($id, $lockMode = null, $lockVersion = null)
 * @method Betaling|null findOneBy(array $criteria, array $orderBy = null)
 * @method Betaling[]    findAll()
 * @method Betaling[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetalingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Betaling::class);
    }

    // /**
    //  * @return Betaling[] Returns an array of Betaling objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Betaling
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
