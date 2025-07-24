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

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(
        AbonneRepository $repoAbonne,
        LivreRepository $repoLivre,
        GenreRepository $repoGenre,
        AuteurRepository $repoAuteur,
        Request $request
    ): Response {
        $word = $request->get("search", "");
        return $this->render('recherche/index.html.twig', [
            'abonnes' => $repoAbonne->search("pseudo", $word),
            'auteurs' => $repoAuteur->search("nom", $word),
            'livres' => $repoLivre->search("titre", $word),
            'genres' => $repoGenre->search("libelle", $word),
            "search_word"   =>  $word
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
