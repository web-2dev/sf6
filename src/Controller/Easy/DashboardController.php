<?php

namespace App\Controller\Easy;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Abonne;
use App\Entity\Auteur;
use App\Entity\Emprunt;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilderInterface)
    {
        $this->chartBuilder = $chartBuilderInterface;
    }

    
    #[Route('/easyredirect')]
    public function index3(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Option 1. Make your dashboard redirect to the same page for all users
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. Make your dashboard redirect to different pages depending on the user
        // if ('jane' === $this->getUser()->getUserIdentifier()) {
            return $this->redirect('...');
        // }
    }

    #[Route("/easy", name:"app_easyadmin")]
    public function index(): Response {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        return $this->render("easy/my-dashboard.html.twig", [ "chart" => $chart ]); 
    }

    #[Route('/easy-default', name: 'admin')]
    public function index_(): Response
    {
        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('')
            ->setTitle("<img src='images/logo.png'> Cours Symfony 6 ")
            ->setFaviconPath("images/logo.png")
            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()

            ;
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
            return [
                MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
    
                MenuItem::section('Usagers'),
                MenuItem::linkToCrud('Livres', 'fa fa-book-skull', Livre::class),
                MenuItem::linkToCrud('Genres', 'fa fa-tags', Genre::class),
                MenuItem::linkToCrud('Auteurs', 'fa-solid fa-user-pen', Auteur::class),
    
                MenuItem::section('Bibliothéchaires'),
                MenuItem::linkToCrud('Emprunts', 'fa fa-address-book', Emprunt::class),

                MenuItem::section('Administrateur'),
                MenuItem::linkToCrud('Abonnés', 'fa fa-user', Abonne::class),
            ];
    }
}
