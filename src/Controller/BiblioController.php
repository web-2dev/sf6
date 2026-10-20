<?php

namespace App\Controller;

    use App\Entity\Livre;
    use App\Form\LivreType;
    use App\Enum\Provenance;
    use App\Entity\Exemplaire;
    use App\Enum\StatutLecture;
    use App\Form\ExemplaireType;
    use App\Repository\LivreRepository;
    use App\Controller\Dev\ParentController;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

#[Route(path: "/espace/ma-biblio")]
class BiblioController extends ParentController
{
    #[Route("/", name:"app_espace_biblio", methods: ["GET"])]
    public function maBiblio(LivreRepository $repoLivre, Request $rq): Response {
        $filtre = $rq->query->get("filtre");
        $statut = $rq->query->get("statut");
        $acquisition = $rq->query->get("acquisition");
        
        if( !$filtre && !$statut && !$acquisition )
            $livresDisponibles =  $repoLivre->findAll(["titre" => "ASC"]);
        else {
            $filtres = [];
            if ( $filtre ) $filtres["titre"] = $filtre;
            if ( $statut ) $filtres["statut"] = $statut;
            if ( $acquisition ) $filtres["acquisition"] = $acquisition;

            $livresDisponibles = $repoLivre->findBy($filtres);
        }

        return $this->render("espace/biblio/ma_biblio", [
            "livresDisponibles" => $livresDisponibles,
            "acquisitionsSortie" => Provenance::sorties()
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

    #[Route("/modifier-mon-exemplaire-{id}", name:"app_espace_biblio_livre_edit", methods: ["GET", "POST"])]
    public function biblioModifierLivre(Exemplaire $exemplaire, Request $rq, EntityManagerInterface $em): Response {

        // Vérifie que l'exemplaire appartient bien au lecteur connecté
        $lecteur = $this->getUser();
        /** @var \App\Entity\Abonne $lecteur*/
        if ($exemplaire->getBiblio() !== $lecteur->getBiblio()) {
            throw $this->createAccessDeniedException();
        }
    
        $form = $this->createForm(ExemplaireType::class, $exemplaire);
    
        $form->handleRequest($rq);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            $this->addFlash(
                'success',
                'Le livre a été modifié dans votre bibliothèque.'
            );
    
            return $this->redirectToRoute('app_espace_biblio');
        }
    
        return $this->render('espace/biblio/exemplaire_modif.html.twig', [
            'exemplaire' => $exemplaire,
            'form' => $form,
        ]);
    }

    #[Route( '/bibliotheque/exemplaire/{id}/retirer', name: 'app_espace_biblio_exemplaire_remove', methods: ['POST'] )]
    public function remove( Exemplaire $exemplaire, Request $request, EntityManagerInterface $em ): Response {
        // L'exemplaire doit appartenir au lecteur connecté
        /** @var \App\Entity\Abonne $lecteur */
        $lecteur = $this->getUser();
        if ($exemplaire->getBiblio() !== $lecteur->getBiblio()) {
            throw $this->createAccessDeniedException();
        }
    
        // CSRF
        if (!$this->isCsrfTokenValid( 'remove_exemplaire_' . $exemplaire->getId(), $request->request->getString('_token') )) {
            throw $this->createAccessDeniedException( 'Token CSRF invalide.' );
        }
    
        // Conversion de la valeur reçue en Enum
        $acquisition = Provenance::tryFrom( $request->request->getString('acquisition') );
    
        if ( $acquisition === null || !in_array($acquisition, Provenance::sorties(), true) ) {
            throw $this->createNotFoundException('Situation invalide.');
        }
    
        $exemplaire->setAcquisition($acquisition);
    
        $em->flush();
    
        $this->addFlash('success', 'Le livre a été retiré de votre bibliothèque.');
    
        return $this->redirectToRoute('app_espace_biblio');
    }
}
