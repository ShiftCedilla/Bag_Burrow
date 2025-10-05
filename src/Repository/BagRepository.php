<?php

namespace App\Repository;

use App\Entity\Bag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bag>
 */
class BagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bag::class);
    }
 /**    * @param User $user
        * @return Bag[] Returns an array of Bag objects
       */
      public function findRequestedByOwner(User $user): array
    {
        return $this->createQueryBuilder('bag')
            ->join('bag.status', 'status')
            ->andWhere('bag.owner = :user')
            ->andWhere('status.name = :demande')
            ->setParameter('user', $user)
            ->setParameter('demande', 'demandé')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Bag
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}