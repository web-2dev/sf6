<?php

namespace App\Repository;

use App\Entity\Auteur;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Auteur>
 */
class AuteurRepository extends ParentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auteur::class);
    }

    //?     ╔═══════════════════════════════════════════════════════════════════════╗       
    //?     ║                        REQUÊTES PERSONNALISÉES                        ║       
    //?     ╚═══════════════════════════════════════════════════════════════════════╝       

    /**
     * @return Auteur[] Returns an array of Auteur objects

      SELECT a 
      FROM auteur a JOIN livre l ON a.id = l.auteur_id 
      GROUP BY a.id 
      HAVING count(*) = (SELECT max(t.nb) 
                         FROM (SELECT count(*) as nb 
                               FROM auteur a JOIN livre l ON a.id = l.auteur_id 
                               GROUP BY a.id) t)      
     */
    public function findProlifique($plus = true): ?Auteur
    {
        // $query = $this->em->createQuery("SELECT a 
        //                                  FROM App\Entity\Auteur a JOIN App\Entity\Livre l WITH a.id = l.auteur 
        //                                  GROUP BY a.id 
        //                                  HAVING count(a) = (SELECT max(t.nb) 
        //                                                     FROM nb_livres_par_auteur t) ");
        // return $query->getResult();
        $sousRequete = $plus ? "SELECT max(t.nb)" : "SELECT min(t.nb)";
        $sousRequete .= " FROM (SELECT count(*) as nb 
                                FROM auteur a JOIN livre l ON a.id = l.auteur_id 
                                GROUP BY a.id) t";
        $requete = "SELECT a.*
                    FROM auteur a JOIN livre l ON a.id = l.auteur_id 
                    GROUP BY a.id 
                    HAVING count(a.id) = ($sousRequete)";

        $pdo = $this->getEntityManager()->getConnection();
        $pdoStatement = $pdo->prepare($requete);
        $result = $pdoStatement->executeQuery();
    
        $resultAuteur = $result->fetchAssociative();  // fetchOne() récupère la valeur de la 1ère colonne
        $auteur = $this->find($resultAuteur["id"]);
        return $auteur;
    }
    
    /**
        SELECT a.nom, a.prenom, l.titre, g.libelle
        FROM auteur a 
            LEFT JOIN livre l ON a.id = l.auteur_id
            JOIN livre_genre lg ON lg.livre_id = l.id
            JOIN genre g ON lg.genre_id = g.id
        WHERE a.nom = "asimov"
        -- GROUP BY l.titre
        GROUP BY g.libelle
        ORDER BY a.nom, a.prenom, l.titre;     
     */
    // public function findGenres(Auteur $a) {
    //     $query = $this->createQueryBuilder("a")
    //                     ->join(Livre::class, "l", "WITH", "a.id = l.auteur")
    //                     ->join("l.genres", "g")
    //                     ->where("a.id = :id")
    //                     ->groupBy("g.libelle")
    //                     ->select("g")
    //                     ->setParameter("id", $a->getId());

    //     return $query->getQuery()->getResult();
    // }
}
