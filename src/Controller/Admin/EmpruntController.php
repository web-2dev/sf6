<?php

namespace App\Controller\Admin;

use App\Entity\Emprunt;
use App\Form\EmpruntType;
use App\Repository\EmpruntRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/emprunt')]
final class EmpruntController extends AbstractController
{
    #[Route(name: 'app_admin_emprunt_index', methods: ['GET'])]
    public function index(EmpruntRepository $empruntRepository): Response
    {
        return $this->render('admin/emprunt/index.html.twig', [
            'emprunts' => $empruntRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_emprunt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emprunt = new Emprunt();
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if( !$emprunt->getDatePrevue() ) {
                $intervalle = new DateInterval($this->getParameter("temps_emprunt_prevu"));
                $date = DateTimeImmutable::createFromInterface($emprunt->getDateEmprunt());
                $emprunt->setDatePrevue( $date->add( $intervalle) );
            }
            $entityManager->persist($emprunt);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/emprunt/new.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form,
            "temps_emprunt_prevu" => $this->getParameter(("temps_emprunt_prevu")),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_emprunt_show', methods: ['GET'])]
    public function show(Emprunt $emprunt): Response
    {
        return $this->render('admin/emprunt/show.html.twig', [
            'emprunt' => $emprunt,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_emprunt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emprunt $emprunt, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if( !$emprunt->getDatePrevue() ) {
                $intervalle = new DateInterval($this->getParameter("temps_emprunt_prevu"));
                $date = DateTimeImmutable::createFromInterface($emprunt->getDateEmprunt());
                $emprunt->setDatePrevue( $date->add( $intervalle) );
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/emprunt/edit.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form,
            "temps_emprunt_prevu" => $this->getParameter(("temps_emprunt_prevu")),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_emprunt_delete', methods: ['POST'])]
    public function delete(Request $request, Emprunt $emprunt, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emprunt->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($emprunt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_emprunt_index', [], Response::HTTP_SEE_OTHER);
    }
}
