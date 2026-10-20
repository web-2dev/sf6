<?php

namespace App\Controller;

use App\Controller\Dev\ParentController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: "/espace")]
#[IsGranted("ROLE_LECTEUR")]
class EspaceController extends ParentController
{
    #[Route('/', name: 'app_espace')]
    public function index(): Response
    {
        return $this->render('espace/index.html.twig', []);
    }

}
