<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AbonneRepository     as AbRepo;
use App\Repository\AuteurRepository     as ARepo;
use App\Repository\EmpruntRepository    as ERepo;
use App\Repository\GenreRepository  as GRepo;
use App\Repository\LivreRepository  as LRepo;

class GestionController extends AbstractController
{
    #[Route('/admin/gestion', name: 'app_admin_gestion')]
    public function index(AbRepo $ar, ARepo $aR, ERepo $er, GRepo $gr, LRepo $lr): Response
    {
        $emprunts = $er->findAll(["dateRetour" => "ASC", "dateEmprunt" => "ASC"]);
        $empruntsEnCours = $er->findByNonRendus();
        
        $emprunts["liste"] = $emprunts;
        $emprunts["nb"] = $er->nb();
        $emprunts["en cours"] = $empruntsEnCours;
        
        $livres["liste"] = $lr->findAll();
        $livres["nb"] = $lr->nb();
        $livres["nbSortis"] = $lr->nbLivresEmpruntes();
        $livres["nbDisponibles"] = $lr->nbLivresDisponibles();
        $livres["plusAncienEmprunt"] = count($empruntsEnCours) ? $empruntsEnCours[0] : null;
        $livres_empruntes = $lr->lesPlusEmpruntes();
        // $livres_empruntes = array_splice($livres_empruntes, 0, 5);
        $livres["plusEmprunte"] = $livres_empruntes ? $livres_empruntes[0] : null;
        $livres["moinsEmprunte"] = $livres_empruntes ? end($livres_empruntes) : null;
        
        $abs = $ar->findOrderedByNbEmprunts();
        $abonnes["liste"]       = $ar->findAll();
        $abonnes["nb"]          = $ar->nb();
        $abonnes["emprunteurs"] = $ar->findByLivresNonRendus();
        $abonnes["assidu"]      = empty($abs) ? null : $abs[0];
        $bibliophiles           = $ar->findOrderedByNbLivresEmpruntes();
        $abonnes["bibliophile"] = $bibliophiles ?  $bibliophiles[0] : null;

        $auteurs["liste"]       = $aR->findAll();
        $auteurs["prolifique"]  = $aR->findProlifique();
        $auteurs["faineant"]    = $aR->findProlifique(false);
        $auteurs["nb"]          = $ar->nb();
        // $auteurs["plebiscite"] = $aR->findPlebiscite();
        // $auteurs["deteste"] = $aR->findDeteste();

        $genres["liste"]            = $gr->findAll();
        $genres["nb"]               = $gr->nb();
        $plusPresent                = $gr->findByNbLivres(true);
        $moinsPresent               = $gr->findByNbLivres(false);
        $genres["nbPlusPresent"]    = $plusPresent ? $plusPresent[0][0]->getLivres()->count() : 0;
        $genres["nbMoinsPresent"]   = $moinsPresent ? $moinsPresent[0][0]->getLivres()->count() : 0;
        $genres["plusPresent"]      = "";
        $genres["moinsPresent"]     = "";
        foreach ($plusPresent as $genre) {
            $genre = $genre[0];
            $genres["plusPresent"] .= ($genres["plusPresent"] ? ", " : "") . $genre->getLibelle();
        }
        foreach ($moinsPresent as $genre) {
            $genre = $genre[0];
            $genres["moinsPresent"] .= ($genres["moinsPresent"] ? ", " : "") . $genre->getLibelle();
        }
        $nombdd = $lr->dbName();
        

        return $this->render("admin/gestion.html.twig", compact("livres", "abonnes", "emprunts", "nombdd", "auteurs", "genres"));
    }
}
