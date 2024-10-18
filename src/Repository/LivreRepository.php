<?php

namespace App\Repository;

use App\Entity\Emprunt;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ParentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    //?     ╔═══════════════════════════════════════════════════════════════════════╗       
    //?     ║                        REQUÊTES PERSONNALISÉES                        ║       
    //?     ╚═══════════════════════════════════════════════════════════════════════╝       


    /**
     * 💬 Livres empruntés (requête avec jointure)                                                                
     * @return Livre[] Retourne les livres qui n'ont pas été rendus      
        SELECT l.*
        FROM livre l JOIN emprunt e ON l.id = e.livre_id
        WHERE e.dateRetour IS NULL
     * 
     */
    public function findLivresEmpruntés(): array {
        return $this->createQueryBuilder("l")   
                    ->join(Emprunt::class, "e", "WITH", "l.id = e.livre") //! ->join("App\Entity\Emprunt", "e", "WITH", "e.livre=l.id")
                    ->where("e.dateRetour IS NULL")     // ->select("l")                            // ! inutile
                    ->orderBy("l.titre")
                    ->getQuery()->getResult();
    }

   /**
     * 💬: Livres disponibles (requêtes imbriquées)
        SELECT l.*
        FROM livre l 
        WHERE l.id NOT IN (
                            SELECT l.id
                            FROM emprunt e JOIN livre l ON e.livre_id = l.id
                            WHERE e.date_retour IS NULL 
                          ) 

     * • L'utilisation de l'entityManager permet d'écrire une requête DQL : 
     * • on n'utilise pas les tables mais les classes entités;
     * • dans la requête imbriquée, on ne peut pas utiliser 2x le même alias pour une table.
     * • Les noms des champs correspondent aux propriétés pas aux colonnes de la bdd.
     * • Dans les jointures, on ne peut pas utiliser ON, plutôt WITH.
     */
    public function livresDisponibles(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT l
             FROM App\Entity\Livre l 
             WHERE l.id NOT IN (SELECT liv.id
                                FROM App\Entity\Emprunt e 
                                JOIN App\Entity\Livre liv WITH e.livre = liv.id
                                WHERE e.dateRetour IS NULL )
            ORDER BY l.titre"
        );
        return $query->getResult();
    }

    /**
     * 💬 Livres les plus empruntés                                                 
     * Requête SQL :
        SELECT l.titre, COUNT(*) AS nb
        FROM livre l
          JOIN emprunt e ON l.id = e.livre_id
        GROUP BY l.titre
        ORDER BY nb DESC, l.titre ASC
     * 
     * @param $max integer
     */
    public function lesPlusEmpruntes(int $max=0)
    {
        /**
         * NB: s'il n'y a pas de champ reliant les deux entités dans l'entité du Repository actuel  
         * NB: il faut préciser les champs liés (? le mot ON ne fonctionne pas)                     
         * NB: dans la méthode select l équivaut à l.*                                              
        */
        $requete = $this->createQueryBuilder("l")
                        ->join(Emprunt::class, "e", "WITH", "l.id = e.livre")
                        ->groupBy("l.titre")
                        ->select("l AS livre, COUNT(l.id) AS nbEmprunts")
                        ->orderBy("nbEmprunts", "DESC")
                        ->addOrderBy("l.titre", "ASC")
        ;
        if($max) $requete->setMaxResults($max);
        return  $requete->getQuery()->getResult();
    }

    /**
      * 💬 COURS  
        Pour créer une nouvelle méthode dans un Repository qui va donc exécuter une requête SELECT
        on utilise la méthode createQueryBuider. A partir de l'objet renvoyé par createQueryBuilder, on peut
        construire la requête en utilsant plusieurs méthodes qui correspondent aux clauses de la requête SQL

        Le paramètre de createQueryBuilder est l'alias de la table sur laquelle on fait la requête (cela dépend donc
        du Repository dans lequel vous écrivez votre code, donc ici il s'agit de la table Livre)
        ensuite vous pouvez enchainer les méthodes join, where, andWhere, orderBy, ....
        Si vous utilisez des paramètres dans une partie de la requête (par exemple :
            ->where("l.titre = :titre)
        n'oubliez pas d'utilier setParameter pour donner une valeur à ce paramètre :
            ->setParameter('titre', $titre)

     * 💬 Recherche d'un mot dans le titre ou le synopsis                       
     */
    public function recherche($mot){
        return $this->createQueryBuilder('l')
            ->where('l.titre LIKE :val OR l.synopsis LIKE :val')
            // ->where("l.titre LIKE :val")
            // ->orWhere("l.synopsis LIKE :val")
            ->setParameter('val', "%$mot%")
            ->orderBy('l.titre', 'ASC')
            ->getQuery()->getResult()
        ;
    }

    /**
     * 💬 Recherche par mot dans le genre ou les mots-clés des genres           
     * 
        SELECT l.*
        FROM livre l 
            JOIN livre_genre lc ON l.id = lc.livre_id
            JOIN genre g ON g.id = lc.genre_id
        WHERE g.mots_cles LIKE "%science%" OR g.libelle LIKE "%science%"
        ORDER BY g.libelle, l.titre;
    */
    public function rechercheParGenres($motRecherche)
    {
         return $this->createQueryBuilder('l')
                     ->join("l.genres", "g")
                     ->where("g.mots_cles LIKE :mot OR g.libelle LIKE :mot")
                     ->setParameter("mot", "%$motRecherche%")
                     ->orderBy("g.libelle")
                     ->addOrderBy("l.titre")
                     ->getQuery()->getResult();
    }
 
    public function findByGenres(int $searchedWord): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT p
             FROM App\Entity\Livre l
                JOIN App\Entity\Genre g 
             WHERE g.mots_cles LIKE :mot OR g.libelle LIKE :mot
             ORDER BY g.libelle ASC, l.titre ASC"
        )->setParameter("mot", "%$searchedWord%");

        return $query->getResult();
    }



    /**
     * SELECT * FROM `produit`
     * WHERE categorie LIKE "%pull%"
     *      OR titre LIKE "%pull%"
     *      OR description LIKE "%pull%"
     */
    public function findByTitreCategorieDescription($recherche){
        
        // version avec EntityManager
        $entityManager = $this->getEntityManager();
        $requete = $entityManager->createQuery("SELECT p 
                                                FROM App\Entity\Produit p 
                                                WHERE p.categorie LIKE '%$recherche%' 
                                                    OR p.titre LIKE '%$recherche%'
                                                    OR p.description LIKE '%$recherche%'");
        return $requete->getResult();

        // version avec CreateQueryBuilder
        return $this->createQueryBuilder('p')
            ->andWhere('p.titre LIKE :val OR  p.categorie LIKE :val OR p.description LIKE :val')
            ->setParameter('val', "%" . $recherche . "%")
            ->orderBy('p.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        

    }


    /**
     * @return Livre[] Retourne les livres qui n'ont pas été rendus
     * 
     */
    public function findByLivresEmpruntes()
    {
        /*
        SELECT l.*
        FROM livre l JOIN emprunt e ON l.id = e.livre_id
        WHERE e.dateRetour IS NULL
        ORDER BY l.auteur ASC, l.titre

        */
        return $this->createQueryBuilder('l')
            ->join(Emprunt::class, "e", "WITH", "l.id = e.livre")
            ->where('e.dateRetour IS NULL')
            ->orderBy('l.auteur', 'ASC')
            ->addOrderBy("l.titre")
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByRecherche($value)
    {
        /*  SELECT l.*
            FROM livre
            WHERE l.titre LIKE :val OR l.auteur LIKE :val */

        return $this->createQueryBuilder('l')
            ->andWhere('l.titre LIKE :val')
            ->orWhere('l.auteur LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('l.auteur')
            ->orderBy('l.titre')
            ->getQuery()
            ->getResult()
        ;
    }



    /**
     * @return Livre[] Retourne les livres qui n'ont pas été rendus
        SELECT l.*
        FROM livre l JOIN emprunt e ON l.id = e.livre_id
        WHERE e.dateRetour IS NULL
     * 
     */
    public function findLivresEmpruntes(): array
    // public function livresIndisponibles(): array
    {
        return $this->createQueryBuilder('l')
            ->join(Emprunt::class, "e", "WITH", "l.id = e.livre")
            //! ->join("App\Entity\Emprunt", "e", "WITH", "e.livre=l.id")
            ->where('e.dateRetour IS NULL')
            // ->select("l")                            // ! inutile
            ->orderBy("l.titre")
            ->getQuery()
            ->getResult()
        ;
    }





    /**
     * Nombre de livres empruntés actuellement
     * @return integer
     * 
       SELECT COUNT(*)
       FROM livre l
        JOIN emprunt e ON l.id = e.livre_id
       WHERE e.dateRetour IS NULL
     */
    public function nbLivresEmpruntes() : int
    {
        $requete = $this->createQueryBuilder("l")
                        ->select("COUNT(l.id) as nb")
                        ->join(Emprunt::class, "e", "WITH", "e.livre = l.id")
                        ->andWhere('e.dateRetour IS NULL')
                        ->getQuery()
                        ->getOneOrNullResult();
        return $requete ? (int)$requete["nb"] : 0;
    }

    /**
     * Nombre de livres disponibles
     * @return integer
     */
    public function nbLivresDisponibles() : int
    {
        return $this->nb() - $this->nbLivresEmpruntes();
    }












   
}
