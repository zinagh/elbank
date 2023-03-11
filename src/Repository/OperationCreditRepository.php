<?php

namespace App\Repository;

use App\Entity\OperationCredit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OperationCredit|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationCredit|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationCredit[]    findAll()
 * @method OperationCredit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationCreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationCredit::class);
    }

    // /**
    //  * @return OperationCredit[] Returns an array of OperationCredit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OperationCredit
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
