<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\AbonneRepository;
use App\Repository\AuteurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(
        AbonneRepository $repoAbonne,
        LivreRepository $repoLivre,
        GenreRepository $repoGenre,
        AuteurRepository $repoAuteur,
        Request $request,
        RouterInterface $router
    ): Response {
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
            
            default:
                return $this->render('recherche/index.html.twig', [
                    'abonnes' => $repoAbonne->search("pseudo", $word),
                    'auteurs' => $repoAuteur->search("nom", $word),
                    'livres' => $repoLivre->search("titre", $word),
                    'genres' => $repoGenre->search("libelle", $word),
                    "search_word"   =>  $word
                ]);
        }
    }

    #[Route("/recherche/auteurs", name:"app_recherche_auteurs")]
    public function auteurs(Request $request, AuteurRepository $repoAuteur): Response {
        $word = $request->get("search", null);
        return $this->render("visiteur/auteurs.html.twig", [ 
            "auteurs"   =>  $repoAuteur->search("nom", $word, ["nom" => "asc", "prenom" => "asc"]), 
            "titre"     =>  "Résultats de la recherche de '<code>" . htmlspecialchars($word) . "</code>'"
        ]); 
    }

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
            'auteurs' => $repoAuteur->search("nom", $word),
            'livres' => $repoLivre->search("titre", $word),
            'genres' => $repoGenre->search("libelle", $word),
        ]);
    }
}
