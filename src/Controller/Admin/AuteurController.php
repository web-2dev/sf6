<?php

namespace App\Controller\Admin;

    use App\Controller\Dev\ParentController;
    use App\Entity\Auteur;
    use App\Form\AuteurType;
    use App\Repository\AuteurRepository;
    use App\Trait\CrudTrait;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/auteur')]
final class AuteurController extends ParentController
{
    use CrudTrait;

    #[Route(name: 'app_admin_auteur_index', methods: ['GET'])]
    public function index(AuteurRepository $auteurRepository, Request $request): Response
    {
        $this->setSortColumn($request);
        switch ($this->col) {
            case "id" : case 'nom':
                $auteurs = $auteurRepository->findAll(["prenom" => $this->dir, "nom" => $this->dir ]);
                break;
            
            case 'dates':
                $auteurs = $auteurRepository->findAll([ "naissance" => $this->dir ]);
                break;
            
            case "bibliographie":
                $auteurs = $auteurRepository->findAll();
                usort(
                    $auteurs,
                    $this->dir == "asc"
                        ? fn (Auteur $a, Auteur $b) => $a->getLivres()->count() <=> $b->getLivres()->count()
                        : fn (Auteur $a, Auteur $b) => $b->getLivres()->count() <=> $a->getLivres()->count()
                );
                break;

            default:
                $auteurs = $auteurRepository->findAll([ $this->col => $this->dir ]);
        }

        return $this->render('admin/auteur/index.html.twig', [
            'auteurs' => $auteurs,
        ]);
    }

    #[Route('/new', name: 'app_admin_auteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/auteur/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_auteur_show', methods: ['GET'])]
    public function show(Auteur $auteur): Response
    {
        return $this->render('admin/auteur/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_auteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/auteur/edit.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_auteur_delete', methods: ['POST'])]
    public function delete(Request $request, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auteur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($auteur);
            $entityManager->flush();
        }

        $redirectUrl = $request->request->get('redirect_url');

        return $this->redirect(
            $redirectUrl ?? $this->generateUrl('app_admin_auteur_index'),
            Response::HTTP_SEE_OTHER
        );

    }
}
