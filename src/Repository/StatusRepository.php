<?php

namespace App\Repository;

use App\Entity\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Status>
 */
class StatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Status::class);

    }

    /**
     * Retourne les saacs avec le statut "disponible"
     */
    public function avaibleBag(): ?Status
    {
        return $this->findOneBy(['name' => 'disponible']);
    }

    /**
     * Retourne les sacs avec le statut "demandé"
     */
    public function DemandeBag(): ?Status
    {
        return $this->findOneBy(['name' => 'demandé']);
    }

    /**
     * Retourne les sacs avec statut "indisponible"
     */
    public function NotAvaibleBag(): ?Status
    {
        return $this->findOneBy(['name' => 'indisponible']);
    }



    //    /**
    //     * @return Status[] Returns an array of Status objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Status
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
