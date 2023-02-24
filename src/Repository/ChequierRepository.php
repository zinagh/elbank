<?php

namespace App\Repository;

use App\Entity\Chequier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chequier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chequier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chequier[]    findAll()
 * @method Chequier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChequierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chequier::class);
    }
    /**
     * @return Chequier[]
     */
    public function triSujetASC(){
        return $this->createQueryBuilder('t')
            ->orderBy('t.date_creation','ASC')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Chequier[]
     */
    public function triSujetDESC(){
        return $this->createQueryBuilder('p')
            ->orderBy('p.date_creation','DESC')
            ->getQuery()
            ->getResult();
    }


    public function findChequierByMotif($motif_chequier){
        return $this->createQueryBuilder('chequier')
            ->where('chequier.motif_chequier LIKE :motif_chequier')
            ->setParameter('motif_chequier', '%'.$motif_chequier.'%')
            ->getQuery()
            ->getArrayResult();
    }

    public function findMonChequier($value)
    {
        return $this->createQueryBuilder('chequier')
            ->join('chequier.num_compte_id', 'cmpt')
            ->addSelect('cmpt')
            ->where('cmpt.id = :val')
            ->setParameter('val', $value)
            ->orderBy('chequier.date_creation', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Chequier[] Returns an array of Chequier objects
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
    public function findOneBySomeField($value): ?Chequier
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