<?php

namespace App\Controller;

use App\Controller\Dev\ParentController;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\AuteurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VisiteurController extends ParentController
{

    /************************************************************ */
    /** GENRES  */
    /************************************************************ */
    #[Route("/genres", name:"app_visiteur_genreS")]
    public function genres(GenreRepository $genreRepository) {
        
        return $this->render("visiteur/genre/liste.html.twig", [ "genres" => $genreRepository->findAll() ]); 
    }

    #[Route("/genre-{libelle}/fiche", name:"app_visiteur_genre_fiche_libelle")]
    #[Route("/genre-{id}/fiche", name:"app_visiteur_genre_fiche", requirements: [ "id" => "\d+" ])]
    public function genreFiche(Genre $genre): Response {
        
        return $this->render("genre/fiche.html.twig", compact("genre")); 
    }

    #[Route("/genres/{libelle}", name:"app_visiteur_genre_fiche")]
    public function genre(Genre $genre) {
        return $this->render("visiteur/genre/fiche.html.twig", compact("genre"));
    }


    
    /************************************************************ */
    /** AUTEURS  */
    /************************************************************ */
    #[Route("/auteurs", name:"app_visiteur_auteurs")]
    public function auteurs(AuteurRepository $auteurRepository) {
        
        return $this->render("visiteur/auteurs.html.twig", [ "auteurs" => $auteurRepository->findAll(["nom" => "asc"]) ]); 
    }

    #[Route("/auteur-{id}/fiche", name:"app_visiteur_auteur_fiche")]
    public function auteurFiche(Auteur $auteur, GenreRepository $gr, AuteurRepository $ar): Response {
        return $this->render("auteur/fiche.html.twig", compact("auteur")); 
    }

    /************************************************************ */
    /** LIVRES  */
    /************************************************************ */

     #[Route("/livres", name:"app_visiteur_livres")]
    public function livres(LivreRepository $livreRepository) {
        
        return $this->render("visiteur/livres.html.twig", [ "livres" => $livreRepository->findAll(["titre" => "asc"]) ]); 
    }
   
    #[Route("/livre/{url}/fiche", name:"app_visiteur_livre_fiche")]
    #[Route("/livre-{id}/fiche", name:"app_visiteur_livre_fiche_id", requirements:["id"=>"\d+"])]
    public function livreFiche(Livre $livre): Response {
        
        return $this->render("visiteur/livre/fiche.html.twig", compact("livre")); 
    }
}
