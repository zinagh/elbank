<?php

namespace App\Repository;

use App\Entity\Compte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Compte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compte[]    findAll()
 * @method Compte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compte::class);
    }

    // /**
    //  * @return Compte[] Returns an array of Compte objects
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
    public function findOneBySomeField($value): ?Compte
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function TriParNomClient()
    {
        return $this->createQueryBuilder('cmpt')
            ->join('cmpt.fullname_client', 'user')
            ->addSelect('user')
            ->orderBy('user.nom_u', 'ASC ')
            ->getQuery()
            ->getResult();
    }

    public function TriParDateCreation()
    {
        return $this->createQueryBuilder('cmpt')
            ->orderBy('cmpt.date_creation','ASC ')
            ->getQuery()->getResult();
    }

    public function TriParEtatCompte()
    {
        return $this->createQueryBuilder('cmpt')
            ->orderBy('cmpt.etat_compte','ASC ')
            ->getQuery()->getResult();
    }

    public function findOneByFullname($value): ?Compte
    {
        return $this->createQueryBuilder('cmpt')
            ->andWhere('cmpt.fullname_client = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByRIB($value): ?Compte
    {
        return $this->createQueryBuilder('cmpt')
            ->andWhere('cmpt.RIB_Compte = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findCompteByName($name)
    {
        return $this->createQueryBuilder('cmpt')
            ->join('cmpt.fullname_client', 'user')
            ->addSelect('user')
            ->where('user.id LIKE :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getResult();
    }
}