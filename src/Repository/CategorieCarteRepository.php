<?php

namespace App\Repository;

use App\Entity\CategorieCarte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieCarte|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieCarte|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieCarte[]    findAll()
 * @method CategorieCarte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieCarteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieCarte::class);
    }

    // /**
    //  * @return CategorieCarte[] Returns an array of CategorieCarte objects
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
    public function findOneBySomeField($value): ?CategorieCarte
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function search($mots = null){
        $query = $this->createQueryBuilder('a');
        $query->where('a.active = 1');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(a.type, a.description) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }

        return $query->getQuery()->getResult();
    }

}