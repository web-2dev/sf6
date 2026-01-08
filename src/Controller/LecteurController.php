<?php

namespace App\Controller;

use App\Entity\Critique;
use App\Entity\Lecture;
use App\Entity\Livre;
use App\Repository\CritiqueRepository;
use App\Repository\LectureRepository;
use App\Repository\LivreRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route("/lecteur/emettre-une-critique-sur-le-livre-{url}", name:"app_lecteur_critique", methods:["GET", "POST"])]
    public function critique(Request $request, Livre $livre, LivreRepository $lr, CritiqueRepository $cr): Response {
        if( !$lr->aDejaLu(abonne: $this->getUser(), livre: $livre) ) {
            throw $this->createAccessDeniedException("Vous n'avez jamais emprunté ce livre");
        }  
        if( $lr->aDejaCommente(abonne: $this->getUser(), livre: $livre) ) {
            throw $this->createAccessDeniedException("Vous avez jamais déjà laissé une note ou une critique à ce livre");
        }  

        if( $request->isMethod("POST") ) {
            $critique = new Critique;
            $critique->setAbonne( $this->getUser() );
            $critique->setLivre( $livre );
            $critique->setNote( $request->request->get("note"));
            $critique->setCommentaire( $request->request->get("commentaire"));
            $cr->save( $critique );
            $this->addFlash("success", "Votre critique du livre ⪻{$livre->getTitre()}⪼ est enregistrée");
            return $this->redirectToRoute("app_home");
        }

        return $this->render("lecteur/critique.html.twig", [ "livre" => $livre ]); 

    }

    #[Route("/mon-espace-lecture", name:"app_espace")]
    public function monEspaceLecture(LectureRepository $lectR): Response {
        $mesLectures = $lectR->lecteur($this->getUser());
        // $mesEmprunts = $emprunt->
        return $this->render("lecteur/index.html.twig", [
            'lectures' => $mesLectures,
        ]); 
    }

    #[Route("/lecteur/livre-{url}/marquer-lu", name:"app_lecteur_livre_lu")]
    public function marquerLu(Livre $livre, LectureRepository $lectR): Response {
        $lecture = new Lecture();
        $lecture->setAbonne( $this->getUser() );
        $lecture->setLivre($livre);
        $lecture->setQuand(new DateTime() );
        $lecture->setNb(1);
        $lectR->save( $lecture );
        return $this->redirectToRoute("app_espace"); 
    }
}
