<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(): Response
    {
        return $this->render('recherche/index.html.twig', [
            'controller_name' => 'RechercheController',
        ]);
    }
    #[Route('/admin/recherche', name: 'app_recherche_admin')]
    public function admin(): Response
    {
        return $this->render('recherche/admin.html.twig', [
            'controller_name' => 'RechercheController',
        ]);
    }
}
