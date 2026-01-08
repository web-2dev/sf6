<?php

namespace App\Repository;

use App\Entity\Abonne;
use App\Entity\Lecture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lecture>
 */
class LectureRepository extends ParentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lecture::class);
    }

    /**
    * @return Lecture[] Returns an array of Lecture objects
    */
    public function lecteur(Abonne $abonne): array
    {
        return $this->createQueryBuilder('le')
            ->innerJoin("le.livre", "l")
            ->addSelect("l")
            ->andWhere('le.abonne = :lecteur')
            ->setParameter('lecteur', $abonne)
            ->orderBy('l.titre', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    //    /**
    //     * @return Lecture[] Returns an array of Lecture objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Lecture
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
