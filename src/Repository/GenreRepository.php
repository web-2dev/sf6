<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Genre>
 */
class GenreRepository extends ParentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    //?     ╔═══════════════════════════════════════════════════════════════════════╗       
    //?     ║                        REQUÊTES PERSONNALISÉES                        ║       
    //?     ╚═══════════════════════════════════════════════════════════════════════╝       

    public function recherche($mot){
        return $this->createQueryBuilder('g')
            ->andWhere('g.libelle LIKE :val OR g.motsCles LIKE :val')
            ->setParameter('val', '%' . $mot . '%')
            ->orderBy('g.libelle', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
     ? Pour faire une jointure DQL sur une relation n-n, dans la méthode 'join', il faut utiliser la propriété
     ? liée à l'entité jointe. Ne pas utiliser WITH...
    */
    public function findNbLivresMaxMin($max = true)//: int
    {
        $temp = $this->createQueryBuilder('g')
                        ->leftJoin("g.livres", "l")
                        ->select("g.libelle, COUNT(l.id) as nb")  // ? pour compter le nb de livres, il faut bien choisir le champ dans COUNT
                        ->groupBy("g.id")
                        ->orderBy("nb", $max ? "DESC" : "ASC")
                        ->setMaxResults(1)
                        ->getQuery()
                        ->getOneOrNullResult()
                        // ->getResult()
        ;
        // return $temp;
        return $temp ? $temp["nb"] : 0;
    }

    public function findByNbLivres($max = true)
    {
        $temp = $this->createQueryBuilder('g')
            ->leftjoin("g.livres", "l")
            ->select("g", "COUNT(l.id) as nb")
            ->groupBy("g.id")
            ->having("nb =" . $this->findNbLivresMaxMin($max))
            ->getQuery()
            ->getResult()
        ;
        return $temp;
    }

    
}
