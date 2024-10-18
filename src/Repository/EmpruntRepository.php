<?php

namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 */
class EmpruntRepository extends ParentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    /**
     * Retourne la liste des emprunts concernant les livres de l'auteur passé en paramètre
     */
    public function findEmpruntsParAuteur($auteur){
        return $this->createQueryBuilder('e')
            ->join("e.livre", "l")
            ->where('l.auteur = :val')
            ->setParameter('val', $auteur)
            ->orderBy('e.dateEmprunt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /** 
     * ? à modifier (afficher les livres) 
     * */
    public function findLivresEmpruntesPar($pseudo)
    {
        return $this->createQueryBuilder('e')
            ->join("e.abonne", "a")
            ->where('a.pseudo = :val')
            ->setParameter('val', $pseudo)
            ->orderBy('e.dateEmprunt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByNonRendus()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.dateRetour IS NULL')
            ->orderBy('e.dateEmprunt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



}
