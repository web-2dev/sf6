<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
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

    // AUTEURS
    #[Route("/auteurs", name:"app_visiteur_auteurs")]
    public function auteurs(AuteurRepository $auteurRepository) {
        
        return $this->render("visiteur/auteurs.html.twig", [ "auteurs" => $auteurRepository->findAll(["nom" => "asc"]) ]); 
    }

    #[Route("/auteur-{id}/fiche", name:"app_visiteur_auteur_fiche")]
    public function auteurFiche(Auteur $auteur): Response {
        
        return $this->render("auteur/fiche.html.twig", compact("auteur")); 
    }

    // LIVRES
    #[Route("/livres", name:"app_visiteur_livre")]
    public function livres(LivreRepository $livreRepository) {
        
        return $this->render("visiteur/livres.html.twig", [ "livres" => $livreRepository->findAll() ]); 
    }

    #[Route("/livre-{id}/fiche", name:"app_visiteur_livre_fiche")]
    public function livreFiche(Livre $livre): Response {
        
        return $this->render("livre/fiche.html.twig", compact("livre")); 
    }

    // GENRES
    #[Route("/genres", name:"app_visiteur_genre")]
    public function genres(GenreRepository $genreRepository) {
        
        return $this->render("visiteur/genres.html.twig", [ "genres" => $genreRepository->findAll() ]); 
    }

    #[Route("/genre-{libelle}/fiche", name:"app_visiteur_genre_fiche")]
    public function genreFiche(Genre $genre): Response {
        
        return $this->render("genre/fiche.html.twig", compact("genre")); 
    }
    

}
