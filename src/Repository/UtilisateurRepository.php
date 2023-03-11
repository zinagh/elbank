<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    // /**
    //  * @return Utilisateur[] Returns an array of Utilisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function TriParDateNaissance()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.date_naissance','ASC ')
            ->getQuery()->getResult();
    }
    public function TriParNom()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom_u','ASC ')
            ->getQuery()->getResult();
    }
    public function Countutilisateur()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.bloquer_token)')
            ->andWhere('u.bloquer_token != :val')
            ->setParameter('val', '')
            ->getQuery()->getSingleScalarResult();

    }
    public function Connecteutilisateur()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.connecte_token)')
            ->andWhere('u.connecte_token != :val')
            ->setParameter('val', '')
            ->getQuery()->getSingleScalarResult();

    }
    public function findByEmailU(string $email): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->where('u.email_u = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
}