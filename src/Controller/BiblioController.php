<?php

namespace App\Controller;

use App\Controller\Dev\ParentController;
use App\Entity\Livre;
use App\Form\LivreType;
use App\Entity\Exemplaire;
use App\Enum\StatutLecture;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: "/espace/ma-biblio")]
class BiblioController extends ParentController
{

    #[Route("/", name:"app_espace_biblio", methods: ["GET"])]
    public function maBiblio(LivreRepository $repoLivre): Response {
        $livresDisponibles =  $repoLivre->findAll(["titre" => "ASC"]);
        return $this->render("espace/biblio/ma_biblio", [
            "livresDisponibles" => $livresDisponibles,
        ]); 
    }


    #[Route("/ajouter-un-livre", name:"app_espace_biblio_livre_add", methods:["POST"])]
    public function biblioAjouterLivre(Request $rq, LivreRepository $repoLivre, EntityManagerInterface $em): Response {
        $exemplaire = new Exemplaire;
        $livre = $rq->request->get("livre");
        $livre = $repoLivre->find( $livre );
        $acquisition = $rq->request->get("acquisition");
        $statut = $rq->request->get('lecture');
        $tome = $rq->request->get("tome");
        $sousTitre = $rq->request->get("sous_titre");

        // 💬 si le string $statut ne correspond pas à une valeur de l'enum, from génère une erreur, tryFrom retourne null  
        // $statut = StatutLecture::from( $statut );
        $statut = StatutLecture::tryfrom( $statut );
        if( !$statut ) $statut = StatutLecture::AUCUN;

        $exemplaire->setStatut( $statut );
        $exemplaire->setLivre( $livre );
        $exemplaire->setAcquisition( $acquisition );
        $exemplaire->settome($tome !== null && $tome !== '' ? (int) $tome : null);
        $exemplaire->setsousTitre($sousTitre !== '' ? $sousTitre : null);
        
        $em->persist( $exemplaire );
        /** @var App\Entity\Abonne $lecteur */
        $lecteur = $this->getUser();
        $lecteur->getBiblio()->addExemplaire( $exemplaire );
        // $exemplaire->setBiblio( $lecteur->getBiblio());
        $em->flush();
        return $this->redirectToRoute("app_espace_biblio"); 
    }

    #[Route("/ajouter-un-nouveau-livre", name:"app_espace_biblio_livre_new", methods:["GET", "POST"])]
    public function biblioNouveauLivre(Request $request, EntityManagerInterface $entityManager): Response {
        
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $titrePropre = $this->slugger->slug(strtolower($livre->getTitre()));
            $livre->setUrl( $titrePropre );

            if( $fichier = $form->get("couverture")->getData() ){ 
                $nomFichier = "$titrePropre.{$fichier->guessExtension()}"; 
                $destination = $this->getParameter("dossier_couvertures");
                $fichier->move($destination, $nomFichier);
                $livre->setCouverture($nomFichier);
            }

            $exemplaire = new Exemplaire();

            $exemplaire->setLivre($livre);
            /** @var App\Entity\Abonne $lecteur */
            $lecteur = $this->getUser();
            $exemplaire->setBiblio($lecteur->getBiblio());
    
            // éventuellement une valeur par défaut
            $exemplaire->setStatut(StatutLecture::A_LIRE);
    
            $entityManager->persist($livre);
            $entityManager->persist($exemplaire);

            $entityManager->flush();
            $this->addFlash("success", "Nouveau livre enregistré et ajouté à la bibliothèque perso");
            return $this->redirectToRoute('app_espace_biblio', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render("espace/biblio/nouveau_livre.html.twig", [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

}
