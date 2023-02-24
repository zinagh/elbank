<?php

namespace App\Repository;

use App\Entity\Compte;
use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    // /**
    //  * @return Transaction[] Returns an array of Transaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function TriEtatTransaction()
    {
        return $this->createQueryBuilder('trans')
            ->orderBy('trans.etat_transaction', 'DESC ')
            ->getQuery()->getResult();
    }

    public function TriNomRecepteur()
    {
        return $this->createQueryBuilder('trans')
            ->orderBy('trans.fullname_recepteur', 'ASC ')
            ->getQuery()->getResult();
    }

    public function TriDate()
    {
        return $this->createQueryBuilder('trans')
            ->orderBy('trans.date_transaction', 'ASC ')
            ->getQuery()->getResult();
    }

    public function TriNomEmetteur()
    {
        return $this->createQueryBuilder('trans')
            ->join('trans.fullname_emetteur', 'cmpt')
            ->addSelect('cmpt')
            ->orderBy('cmpt.fullname_client', 'ASC ')
            ->getQuery()
            ->getResult();
    }

    public function findByRIBEmetteur($value)
    {
        return $this->createQueryBuilder('trans')
            ->join('trans.RIB_emetteur', 'cmpt')
            ->addSelect('cmpt')
            ->where('cmpt.RIB_Compte = :val')
            ->setParameter('val', $value)
            ->orderBy('trans.date_transaction', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

//    public function StatsEmetteur($value)
//    {
//        return $this->createQueryBuilder('trans')
//            ->join('trans.RIB_emetteur', 'cmpt')
//            ->addSelect('cmpt')
//            ->where('cmpt.RIB_Compte = :val')
//            ->setParameter('val', $value)
//            ->select('SUM(trans.montant_transaction)')
//            ->groupBy(Date('trans.date_transaction'))
//            ->getQuery()
//            ->getSingleScalarResult();
//    }

    public function findByRIBEmetteur2($value)
    {
        return $this->createQueryBuilder('trans')
            ->join('trans.RIB_emetteur', 'cmpt')
            ->addSelect('cmpt')
            ->where('cmpt.RIB_Compte = :val')
            ->setParameter('val', $value)
            ->orderBy('trans.date_transaction', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByRIBRecepteur2($value)
    {
        return $this->createQueryBuilder('trans')
            ->andWhere('trans.RIB_recepteur = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function SommeAujourdhui($value, $date)
    {
        return $this->createQueryBuilder('trans')
//            ->join('trans.RIB_emetteur', 'cmpt')
//            ->addSelect('cmpt')
//            ->where('cmpt.RIB_Compte = :val')
            ->select('sum(trans.montant_transaction)')

            ->andWhere('trans.RIB_recepteur LIKE :val')
            ->andWhere('trans.date_transaction = :ajd')
            ->setParameter('ajd', $date)
            ->setParameter('val', $value)
            ->getQuery()
            ->getSingleScalarResult();
        }
}