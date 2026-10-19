<?php

namespace App\Controller;

use App\Controller\Dev\ParentController;
use App\Entity\Exemplaire;
use App\Entity\Livre;
use App\Enum\StatutLecture;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
