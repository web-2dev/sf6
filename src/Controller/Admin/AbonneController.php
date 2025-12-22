<?php

namespace App\Controller\Admin;

use App\Controller\Dev\ParentController;
use App\Entity\Abonne;
use App\Form\AbonneType;
use App\Repository\AbonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestion/abonne')]
final class AbonneController extends ParentController
{
    #[Route(name: 'app_admin_abonne_index', methods: ['GET'])]
    public function index(AbonneRepository $abonneRepository): Response
    {
        return $this->render('admin/abonne/index.html.twig', [
            'abonnes' => $abonneRepository->findAll(),
        ]);
    }

    #[Route(path: "/lecteurs", name: 'app_admin_abonne_lecteurs', methods: ['GET'])]
    public function readers(AbonneRepository $abonneRepository): Response
    {
        return $this->render('admin/abonne/index.html.twig', [
            'abonnes' => $abonneRepository->findLecteurs(),
        ]);
    }

    #[Route(path: "/bibliothecaires", name: 'app_admin_abonne_bibliothecaires', methods: ['GET'])]
    public function librarians(AbonneRepository $abonneRepository): Response
    {
        return $this->render('admin/abonne/index.html.twig', [
            'abonnes' => $abonneRepository->findBiblios(),
        ]);
    }

    #[Route('/new', name: 'app_admin_abonne_new', methods: ['GET', 'POST'])]
    public function new(Request $request
                        , EntityManagerInterface $entityManager
                        , UserPasswordHasherInterface $hasher): Response
    {
        $abonne = new Abonne();
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();

            // encode the plain password
            $abonne->setPassword($hasher->hashPassword($abonne, $plainPassword));
            $entityManager->persist($abonne);
            $entityManager->flush();

            $this->addFlash("success", "Nouvel usager enregistré");
            return $this->redirectToRoute('app_admin_abonne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/abonne/new.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_abonne_show', methods: ['GET'])]
    public function show(Abonne $abonne): Response
    {
        return $this->render('admin/abonne/show.html.twig', [
            'abonne' => $abonne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_abonne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request
                         , Abonne $abonne
                         , UserPasswordHasherInterface $hasher
                         , EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if( $plainPassword ) {
                $abonne->setPassword($hasher->hashPassword($abonne, $plainPassword));
            }
            $entityManager->flush();
            $this->addFlash("success", "Usager modifié");
            return $this->redirectToRoute('app_admin_abonne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/abonne/edit.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_abonne_delete', methods: ['POST'])]
    public function delete(Request $request, Abonne $abonne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonne->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($abonne);
            $entityManager->flush();
            $this->addFlash("success", "Usager supprimé");
        }

        return $this->redirectToRoute('app_admin_abonne_index', [], Response::HTTP_SEE_OTHER);
    }
}
