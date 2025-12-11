<?php

namespace App\Controller\Admin;

use App\Entity\Critique;
use App\Form\CritiqueType;
use App\Repository\CritiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/critique')]
final class CritiqueController extends AbstractController
{
    #[Route(name: 'app_admin_critique_index', methods: ['GET'])]
    public function index(CritiqueRepository $critiqueRepository): Response
    {
        return $this->render('admin/critique/index.html.twig', [
            'critiques' => $critiqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_critique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $critique = new Critique();
        $form = $this->createForm(CritiqueType::class, $critique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($critique);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_critique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/critique/new.html.twig', [
            'critique' => $critique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_critique_show', methods: ['GET'])]
    public function show(Critique $critique): Response
    {
        return $this->render('admin/critique/show.html.twig', [
            'critique' => $critique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_critique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Critique $critique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CritiqueType::class, $critique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_critique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/critique/edit.html.twig', [
            'critique' => $critique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_critique_delete', methods: ['POST'])]
    public function delete(Request $request, Critique $critique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$critique->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($critique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_critique_index', [], Response::HTTP_SEE_OTHER);
    }
}
