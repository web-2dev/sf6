<?php

namespace App\Controller;

use App\Controller\Dev\ParentController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RechercheController extends ParentController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(): Response
    {
        return $this->render('recherche/index.html.twig', [
            'controller_name' => 'RechercheController',
        ]);
    }
    #[Route('/admin/recherche', name: 'app_admin_recherche')]
    public function admin(): Response
    {
        return $this->render('recherche/admin.html.twig', [
            'controller_name' => 'RechercheController',
        ]);
    }
}
