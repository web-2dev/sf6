<?php

namespace App\Controller\Admin;

use App\Controller\Dev\ParentController;
use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/livre')]
final class LivreController extends ParentController
{
    #[Route(name: 'app_admin_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository, Request $request): Response
    {
        $this->setSortColumn($request);
        // $col = $request->query->get("col", "titre");
        // $dir = $request->query->get("dir", "asc");
        switch ($this->col) {
            case "auteur":
                $livres = $livreRepository->findAllOrderedByRelation($this->col, ["prenom" => $this->dir, "nom" => $this->dir]);
                break;
            
            case "biblios":
                $livres = $livreRepository->findAll();
                usort(
                    $livres,
                    $this->dir == "asc" 
                        ? fn (Livre $a, Livre $b) => $a->getExemplaires()->count() <=> $b->getExemplaires()->count()
                        : fn (Livre $a, Livre $b) => $b->getExemplaires()->count() <=> $a->getExemplaires()->count()
                );                
                break;

            default:
                $livres = $livreRepository->findAll([$this->col => $this->dir]);
                break;
        }

        return $this->render('admin/livre/index.html.twig', compact("livres"));
    }

    #[Route('/new', name: 'app_admin_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $titrePropre = $this->slugger->slug(strtolower($livre->getTitre()));
            $livre->setUrl( $titrePropre );

            if( $fichier = $form->get("couverture")->getData() ){ // pour récupérer les informations du fichier uploadé
                /* pathinfo: fonction qui permet de récupérer des informations sur un fichier, par exemple le nom du fichier sans le chemin complet, sans l'extension  
                    getClientOriginalName : récupère le nom du fichier uploadé (la méthode est exécuté à partir de l'objet instancié par getData() que l'on a exécuté sur l'objet  formulaire)*/
                // $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                // // $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);
                // $nomFichier .= "_" . time();
                // $nomFichier .= "." . $fichier->guessExtension();
                // $nomFichier = str_replace(" ", "_", $nomFichier);
                // $destination = $this->getParameter("dossier_images") . "livres";
                // /* la méthode move va copier le fichier uploadé dans le dossier $destination avec le nom $nomFichier*/
                // $fichier->move($destination, $nomFichier);
                // $livre->setCouverture($nomFichier);

                $nomFichier = "$titrePropre.{$fichier->guessExtension()}"; 
                $destination = $this->getParameter("dossier_couvertures");
                $fichier->move($destination, $nomFichier);
                $livre->setCouverture($nomFichier);
            }
            $entityManager->persist($livre);
            $entityManager->flush();
            $this->addFlash("success", "Nouveau livre n°{$livre->getId()} enregistré");
            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('admin/livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livre->setUrl( $this->slugger->slug(strtolower($livre->getTitre())) );

            if( $fichier = $form->get("couverture")->getData() ){ 

                $nomFichier = "{$livre->getUrl()}.{$fichier->guessExtension()}"; 
                $destination = $this->getParameter("dossier_couvertures");
                if( file_exists($ancienFichier = $this->getParameter("dossier_couvertures") . $livre->getCouverture()) ){
                    unlink($ancienFichier);
                }
                $fichier->move($destination, $nomFichier);
                $livre->setCouverture($nomFichier);
            }
            $entityManager->flush();
            $this->addFlash("success", "Livre n°{$livre->getId()} modifié");
            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->getPayload()->getString('_token'))) {
            $id = $livre->getId();
            $entityManager->remove($livre);
            $entityManager->flush();
            $this->addFlash("success", "Livre n°$id supprimé");
        } else {
            $this->addFlash("warning", "Livre n°{$livre->getId()} : Échec de la suppression");
        }

        return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
