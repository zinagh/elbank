<?php

namespace App\Repository;

use App\Entity\Credit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Credit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Credit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Credit[]    findAll()
 * @method Credit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Credit::class);
    }

    // /**
    //  * @return Credit[] Returns an array of Credit objects
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
    public function findOneBySomeField($value): ?Credit
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    ->add('montCredit')
            ->add('datepe')
            ->add('datede')
            ->add('dureeC')
            ->add('echeance')
            ->add('tauxInteret')
            ->add('decision')
            ->add('etatCredit')
            ->add('typeCredit')
    */
    public function TrimontCredit()
    {
        return $this->createQueryBuilder('Credit')
            ->orderBy('Credit.montCredit', 'DESC ')
            ->getQuery()->getResult();
    }

    public function TriDatepe()
    {
        return $this->createQueryBuilder('Credit')
            ->orderBy('Credit.datepe', 'ASC ')
            ->getQuery()->getResult();
    }

    public function TriDatede()
    {
        return $this->createQueryBuilder('Credit')
            ->orderBy('Credit.datede', 'ASC ')
            ->getQuery()->getResult();
    }

    public function getecheances()
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT C.echeance FROM App\Entity\Credit C GROUP BY C.echeance');
        return $query->getResult();
    }

    public function getecheancesnumber()
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT COUNT(C.id) FROM App\Entity\Credit C GROUP BY C.echeance');
        return $query->getResult();
    }

}