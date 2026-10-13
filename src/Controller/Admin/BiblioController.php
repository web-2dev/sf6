<?php

namespace App\Controller\Admin;

use App\Entity\Biblio;
use App\Form\BiblioType;
use App\Repository\AbonneRepository;
use App\Repository\BiblioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/biblio')]
final class BiblioController extends AbstractController
{
    #[Route(name: 'app_admin_biblio_index', methods: ['GET'])]
    public function index(BiblioRepository $biblioRepository): Response
    {
        return $this->render('admin/biblio/index.html.twig', [
            'biblios' => $biblioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_biblio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $biblio = new Biblio();
        $form = $this->createForm(BiblioType::class, $biblio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($biblio);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_biblio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/biblio/new.html.twig', [
            'biblio' => $biblio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_biblio_show', methods: ['GET'])]
    public function show(Biblio $biblio): Response
    {
        return $this->render('admin/biblio/show.html.twig', [
            'biblio' => $biblio,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_biblio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BiblioType::class, $biblio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_biblio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/biblio/edit.html.twig', [
            'biblio' => $biblio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_biblio_delete', methods: ['POST'])]
    public function delete(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$biblio->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($biblio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_biblio_index', [], Response::HTTP_SEE_OTHER);
    }

    #################### DED
    // #[Route("/dev", name:"app_admin_biblio_dev", priority: 1)]
    // public function dev(AbonneRepository $repoAbonne, EntityManagerInterface $em): Response {
    //     $lecteurs = $repoAbonne->findAll();
    //     /** @var \App\Entity\Abonne $lecteur */
    //     foreach ($lecteurs as $lecteur) {
    //         if( in_array("ROLE_LECTEUR", $lecteur->getRoles() )  ) {
    //             $biblio = new Biblio;
    //             $biblio->setLecteur($lecteur);
    //             $biblio->setNom("Biblio de " . $lecteur->getPseudo());
    //             $em->persist($biblio);
    //         }
    //     }
    //     $em->flush();
    //     return $this->redirectToRoute("app_admin_biblio_index"); 
    // }
}
