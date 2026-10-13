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

    #[Route("/ma-biblio", name:"app_espace_biblio", methods: ["GET"])]
    public function maBiblio(LivreRepository $repoLivre): Response {
        $livresDisponibles =  $repoLivre->findAll(["titre" => "ASC"]);
        return $this->render("espace/ma-biblio", [
            "livresDisponibles" => $livresDisponibles,
        ]); 
    }


    #[Route("/ma-biblio/ajouter-un-livre", name:"app_espace_biblio_livre_add", methods:["POST"])]
    public function biblioAjouterLivre(Request $rq, LivreRepository $repoLivre, EntityManagerInterface $em): Response {
        $exemplaire = new Exemplaire;
        $livre = $rq->request->get("livre");
        $livre = $repoLivre->find( $livre );
        $acquisition = $rq->request->get("acquisition");
        $statut = $rq->request->get('lecture');
        $statut = StatutLecture::from( $statut );

        $exemplaire->setStatut( $statut );
        $exemplaire->setLivre( $livre );
        $exemplaire->setAcquisition( $acquisition );
        
        $em->persist( $exemplaire );
        /** @var App\Entity\Abonne $lecteur */
        $lecteur = $this->getUser();
        $lecteur->getBiblio()->addExemplaire( $exemplaire );
        // $exemplaire->setBiblio( $lecteur->getBiblio());
        $em->flush();
        return $this->redirectToRoute("app_espace_biblio"); 
    }
}
