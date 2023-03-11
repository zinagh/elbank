<?php

namespace App\Repository;

use App\Entity\Destinataire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Destinataire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Destinataire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Destinataire[]    findAll()
 * @method Destinataire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DestinataireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Destinataire::class);
    }

    // /**
    //  * @return Destinataire[] Returns an array of Destinataire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Destinataire
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
