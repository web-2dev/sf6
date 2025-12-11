<?php

namespace App\Controller;

use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_LECTEUR")]
class LecteurController extends AbstractController
{
    #[Route('/lecteur', name: 'app_lecteur')]
    public function index(): Response
    {
        return $this->render('lecteur/index.html.twig', [
            'controller_name' => 'LecteurController',
        ]);
    }

    #[Route("/lecteur/emettre-une-critique-sur-le-livre-{id}", name:"app_lecteur_critique", methods:["GET", "POST"])]
    public function critique(Livre $livre): Response {
        
        return $this->render("lecteur/critique.html.twig", [ "livre" => $livre ]); 
    }

    #[Route("/mon-espace-lecture", name:"app_espace")]
    public function fonction(): Response {
        
        return $this->render("lecteur/index.html.twig", [
            'controller_name' => 'LecteurController',
        ]); 
    }
}
