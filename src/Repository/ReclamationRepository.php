<?php

namespace App\Repository;

use App\Entity\Chequier;
use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }
    /**
     * @return Chequier[]
     */
    public function triSujetASC(){
        return $this->createQueryBuilder('t')
            ->orderBy('t.datecreation','ASC')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Chequier[]
     */
    public function triSujetDESC(){
        return $this->createQueryBuilder('p')
            ->orderBy('p.datecreation','DESC')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Reclamation[] Returns an array of Reclamation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reclamation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function TriParDateReclamation()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.date_rec','ASC ')
            ->getQuery()->getResult();
    }
    public function TriParTypeReclamation()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.type_rec','ASC ')
            ->getQuery()->getResult();
    }
}