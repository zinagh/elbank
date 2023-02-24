<?php

namespace App\Repository;

use App\Entity\Cheques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cheques|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cheques|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cheques[]    findAll()
 * @method Cheques[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChequesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cheques::class);
    }
    /**
     * @return Cheques[]
     */
    public function triSujetASC(){
        return $this->createQueryBuilder('t')
            ->orderBy('t.date_cheque','ASC')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Cheques[]
     */
    public function triSujetDESC(){
        return $this->createQueryBuilder('p')
            ->orderBy('p.date_cheque','DESC')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Cheques[] Returns an array of Cheques objects
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
    public function findOneBySomeField($value): ?Cheques
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