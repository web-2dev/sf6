<?php

namespace App\Repository;

use App\Entity\Abonne;
use App\Entity\Emprunt;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<Abonne>
 */
class AbonneRepository extends ParentRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonne::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Abonne) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    //?     ╔═══════════════════════════════════════════════════════════════════════╗       
    //?     ║                        REQUÊTES PERSONNALISÉES                        ║       
    //?     ╚═══════════════════════════════════════════════════════════════════════╝       

    
    /**
     * Abonnés qui ont des livres non rendus
        SELECT a.*
        FROM abonne a JOIN emprunt e ON a.id = e.abonne_id
        WHERE e.dateRetour IS NULL
     * NB: la jointure peut être définiée à partir des entités  
     */
    public function findByLivresNonRendus(){
        $requete = $this->createQueryBuilder("a")
                        ->join(Emprunt::class, "e", "WITH", "a = e.abonne")
                        ->where("e.dateRetour IS NULL");

        return $requete->getQuery()->getResult();
    }

    /**
     *? Abonnés ordonnés par le nombre d'emprunts
     * 
     *? SELECT a.* AS abonne, COUNT(*) AS nb_emprunts
     *? FROM abonne a JOIN emprunt e ON a.id = e.abonne_id
     *? GROUP BY a.id
     *? ORDER BY nb_emprunts DESC, a.pseudo
     * 
     *? NB : donner un alias à a.* permet de le récupérer comme indice du tableau de résultat
     */
    public function findOrderedByNbEmprunts()
    {
        $requete = $this->createQueryBuilder("a")
                        ->select("a AS abonne, COUNT(a) AS nb_emprunts")
                        ->join(Emprunt::class, "e", "WITH", "a = e.abonne")
                        ->groupBy("a.id")
                        ->orderBy("nb_emprunts", "DESC")->addOrderBy("a.pseudo");
        return $requete->getQuery()->getResult();
    }

    /**
     *? Abonnés ordonnés par le nombre de livres différents empruntés
     * 
     *? SELECT a.*, COUNT(DISTINCT e.livre_id) AS nb_livres_empruntes
     *? FROM abonne a JOIN emprunt e ON a.id = e.abonne_id
     *? GROUP BY a.id 
     *? ORDER BY nb_livres_empruntes DESC, a.pseudo
     * 
     */
    public function findOrderedByNbLivresEmpruntes()
    {
        $requete = $this->createQueryBuilder("a")
                        ->select("a AS abonne, COUNT(DISTINCT e.livre) AS nb_livres_empruntes")
                        ->join(Emprunt::class, "e", "WITH", "a = e.abonne")
                        ->groupBy("a.id")
                        ->orderBy("nb_livres_empruntes", "DESC")->addOrderBy("a.pseudo");
        return $requete->getQuery()->getResult();
    }




}
