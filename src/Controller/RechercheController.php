<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\AbonneRepository;
use App\Repository\AuteurRepository;
use App\Controller\Dev\ParentController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RechercheController extends ParentController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(AbonneRepository $repoAbonne,
                          LivreRepository $repoLivre,
                          GenreRepository $repoGenre,
                          AuteurRepository $repoAuteur,
                          Request $request,
                          RouterInterface $router ): Response {

        $word = $request->get("search", "");
        $nomRoute = "";
        if ( $ref = $request->headers->get("referer") ){
            $chemin = parse_url($ref, PHP_URL_PATH);
            $parametresRoute = $router->match( $chemin );
            $nomRoute = $parametresRoute["_route"];
        }

        switch ($nomRoute) {
            case 'app_visiteur_auteurs':
            case 'app_recherche_auteurs':
                return $this->redirectToRoute('app_recherche_auteurs', ["search" => $word]);
                break;
                
            case 'app_visiteur_livres':
            case 'app_recherche_livres':
                return $this->redirectToRoute('app_recherche_livres', ["search" => $word]);
                break;
            
            case 'app_visiteur_genres':
            case 'app_recherche_genres':
                return $this->redirectToRoute('app_recherche_genres', ["search" => $word]);
                break;
            
            default:
                return $this->render('recherche/index.html.twig', [
                    'abonnes' => $repoAbonne->search("pseudo", $word),
                    'auteurs' => $repoAuteur->search(["nom", "prenom"], $word),
                    'livres' => $repoLivre->search("titre", $word),
                    'genres' => $repoGenre->search(["libelle", "mots_cles"], $word),
                    "search_word"   =>  $word
                ]);
        }
    }

    #[Route("/recherche/auteurs", name:"app_recherche_auteurs")]
    public function auteurs(Request $request, AuteurRepository $repoAuteur): Response {
        $word = $request->get("search", null);
        return $this->render("visiteur/auteurs.html.twig", [ 
            "auteurs"   =>  $repoAuteur->search(["nom", "prenom"], $word, ["nom" => "asc", "prenom" => "asc"]), 
            "word"      =>  htmlspecialchars($word)
        ]); 
    }

    #[Route("/recherche/livres", name:"app_recherche_livres")]
    public function livres(Request $request, LivreRepository $repoLivre): Response {
        $word = $request->get("search", null);
        return $this->render("visiteur/livres.html.twig", [ 
            "livres"    =>  $repoLivre->search("titre", $word, ["titre" => "asc"]), 
            "word"      =>  htmlspecialchars($word)
        ]); 
    }

    #[Route("/recherche/genres", name:"app_recherche_genres")]
    public function genres(Request $request, GenreRepository $repoGenre): Response {
        $word = $request->get("search", null);
        return $this->render("visiteur/genres.html.twig", [ 
            "genres"    =>  $repoGenre->search(["libelle", "motsCles"], $word, ["libelle" => "asc"]), 
            "word"      =>  htmlspecialchars($word)
        ]); 
    }



    /* ADMIN */
    #[Route('/admin/recherche', name: 'app_admin_recherche')]
    public function admin(
        AbonneRepository $repoAbonne,
        LivreRepository $repoLivre,
        GenreRepository $repoGenre,
        AuteurRepository $repoAuteur,
        Request $request
    ): Response {
        $word = $request->get("search", "");
        return $this->render('recherche/admin.html.twig', [
            'abonnes' => $repoAbonne->search("pseudo", $word),
            'auteurs' => $repoAuteur->search(["nom", "prenom"], $word),
            'livres' => $repoLivre->search("titre", $word),
            'genres' => $repoGenre->search("libelle", $word),
        ]);
    }
}
