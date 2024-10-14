<?php

namespace App\Controller;

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

    #[Route("/genres", name:"app_visiteur_genre")]
    public function genres(GenreRepository $genreRepository) {
        
        return $this->render("visiteur/genres.html.twig", [ "genres" => $genreRepository->findAll() ]); 
    }

    #[Route("/auteurs", name:"app_visiteur_auteur")]
    public function auteurs(AuteurRepository $auteurRepository) {
        
        return $this->render("visiteur/auteurs.html.twig", [ "auteurs" => $auteurRepository->findAll() ]); 
    }

    #[Route("/livres", name:"app_visiteur_livre")]
    public function livres(LivreRepository $livreRepository) {
        
        return $this->render("visiteur/livres.html.twig", [ "livres" => $livreRepository->findAll() ]); 
    }

}
