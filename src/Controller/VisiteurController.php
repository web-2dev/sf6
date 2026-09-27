<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\AuteurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VisiteurController extends AbstractController
{
    #[Route('/visiteur', name: 'app_visiteur')]
    public function index(): Response
    {
        return $this->render('visiteur/index.html.twig', [
            'controller_name' => 'VisiteurController',
        ]);
    }

    /************************************************************ */
    /** GENRES  */
    /************************************************************ */
    #[Route("/genres", name:"app_visiteur_genre")]
    public function genres(GenreRepository $genreRepository) {
        
        return $this->render("visiteur/genre/liste.html.twig", [ "genres" => $genreRepository->findAll() ]); 
    }

    #[Route("/genres/{libelle}", name:"app_visiteur_genre_fiche")]
    public function genre(Genre $genre) {
        return $this->render("visiteur/genre/fiche.html.twig", compact("genre"));
    }


    
    /************************************************************ */
    /** AUTEURS  */
    /************************************************************ */
    #[Route("/auteurs", name:"app_visiteur_auteur")]
    public function auteurs(AuteurRepository $auteurRepository) {
        
        return $this->render("visiteur/auteurs.html.twig", [ "auteurs" => $auteurRepository->findAll() ]); 
    }

    /************************************************************ */
    /** LIVRES  */
    /************************************************************ */

    #[Route("/fiche-livre-{id}", name:"app_visiteur_livre_fiche", requirements:["id"=>"\d+"])]
    public function livre(Livre $livre)
    {
        return $this->render("visiteur/livre/fiche.html.twig", [ "livre" => $livre ]);
    }

}
