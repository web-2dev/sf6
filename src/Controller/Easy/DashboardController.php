<?php

namespace App\Controller\Easy;

    use App\Entity\Genre;
    use App\Entity\Livre;
    use App\Entity\Abonne;
    use App\Entity\Auteur;
    use App\Entity\Emprunt;
    use App\Repository\AbonneRepository;
    use App\Repository\AuteurRepository;
    use App\Repository\LivreRepository;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
    use Symfony\UX\Chartjs\Model\Chart;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
    use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
    use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Option\SearchMode;
    use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

    
class DashboardController extends AbstractDashboardController
{
    private ChartBuilderInterface $chartBuilder;
    private AbonneRepository $abonneRepository;
    private LivreRepository $livreRepository;
    private AuteurRepository $auteurRepository;

    public function __construct(ChartBuilderInterface $chartBuilderInterface, AbonneRepository $abRepo, LivreRepository $lRepo, AuteurRepository $aRepo)
    {
        $this->chartBuilder = $chartBuilderInterface;
        
        $this->abonneRepository = $abRepo ;
        $this->livreRepository = $lRepo ;
        $this->auteurRepository = $aRepo;
    }

    
    #[Route("/easy", name:"app_easyadmin")]
    public function index(): Response {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);
        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);        


        return $this->render("easy/my-dashboard.html.twig",compact( "chart" , "chart2")); 
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
                // 💬 lien vers le tableau de bord                                                                      
                MenuItem::linkToDashboard('Dashboard', 'fa fa-chalkboard'),
                MenuItem::linkToRoute("Accueil site", "fa fa-home", "app_home"),
                // MenuItem::linkToUrl("Accueil site", "fa fa-home", $this->generateUrl("app_home")),
    
                // 💬 élément de menu sans lien                                                                         
                MenuItem::section('Usagers'),

                // 💬 liens vers les CRUD                                                                               
                // MenuItem::linkToCrud('Livres', 'fa fa-book-skull', Livre::class),
                MenuItem::subMenu('Livres', 'fa fa-book')->setSubItems([
                    MenuItem::linkToCrud('Liste', 'fa fa-list', Livre::class),
                    MenuItem::linkToCrud('Ajouter', 'fa fa-file-text', Livre::class)->setAction("new"),
                ]),
        
                MenuItem::linkToCrud('Genres', 'fa fa-tags', Genre::class),
                MenuItem::linkToCrud('Auteurs', 'fa-solid fa-user-pen', Auteur::class),
    
                MenuItem::section('Bibliothéchaires'),
                MenuItem::linkToCrud('Emprunts', 'fa fa-address-book', Emprunt::class)
                    ->setPermission("ROLE_BIBLIO"),

                MenuItem::section('Administrateur'),

                MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', Abonne::class)
                    // 💬 ajouter un badge sur un menu                                                                 
                    ->setBadge(3, "danger")
                    ->setPermission("ROLE_ADMIN"),
                MenuItem::section(""),
                MenuItem::linkToCrud('Abonnés', 'fa fa-user', Abonne::class)
                    ->setQueryParameter("roles", "['ROLE_LECTEUR', 'ROLE_USER']")
                    ->setDefaultSort(["pseudo" => "ASC"]),
                MenuItem::linkToCrud('Lecteurs', 'fa fa-user', Abonne::class)->setAction("lecteurs"),
                
                MenuItem::section(""),

                MenuItem::linkToCrud('Ajouter un utilisateur', 'fa fa-user-plus', Abonne::class)->setAction("new"),
                MenuItem::linkToCrud('Administrateur : Amin Strateur', 'fa fa-user-plus', Abonne::class)
                    ->setAction('detail')
                    ->setEntityId(138),
                
                MenuItem::section(""),
                MenuItem::linkToRoute('Gestion', 'fa fa-cogs', 'app_admin_gestion'),
                    
                MenuItem::section("<hr>"),
                MenuItem::linkToExitImpersonation('Stop impersonation', 'fa fa-exit'),
                MenuItem::linkToLogout('Déconnexion', 'fa fa-exit'),

            ];
    }


    /** 
     * Placé dans le DashboardController, permet de configurer tous les CRUD.
     */
    public function configureCrud(): Crud
    {
        // dump($crud);
        return Crud::new()
            // ->renderSidebarMinimized()  // menu latéral réduit
            ->setPageTitle('index', 'Liste des %entity_label_plural%')  // titre h1
            ->setPageTitle(Crud::PAGE_DETAIL, 'Fiche %entity_label_plural%')  // titre h1
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un(e) %entity_label_plural%')  // titre h1
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un(e) %entity_label_singular%')  // titre h1

            ->setDateFormat('dd/MM/Y')
            ->setDateIntervalFormat(' %%d jours(s) %%m mois %%y année(s)')

            ->setThousandsSeparator('.')
            ->setDecimalSeparator(',')

            ->setAutofocusSearch()                      // 💬 place le focus automatiquement sur la recherche   
            ->setSearchMode(SearchMode::ALL_TERMS)      // 💬 ANY_TERMS : pour chercher au moins un mot (utiliser les " pour chercher les correspondances exactes)    

            ->setPaginatorPageSize(10)
            ->setPaginatorRangeSize(4)
        ;
    }


    public function configureActions(): Actions {
        $actions = parent::configureActions();
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }


    //////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////
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

    #[Route("/easy-admin", name:"app_easy_admin")]
    public function tableau2bord(LivreRepository $livreR,
                                 AbonneRepository $abonneR,
                                 AuteurRepository $auteurR 
    ): Response {
        
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);
        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);        

        $chart2 = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chart2->setData([
            "labels"    =>  [ "Abonnés", "Livres", "Auteurs", ],
            "datasets"  =>  [
                [
                    "label"             =>  "Statistiques",
                    "backgroundColor"   =>  "#ececec",
                    "borderColor"       =>  "blue",
                    "data"              =>  [ $abonneR->nb(), $livreR->nb(), $auteurR->nb()]
                ]
            ]
        ]);
        $chart2->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 50,
                ],
            ],
        ]);        



        return $this->render("easy/my-dashboard.html.twig",compact( "chart" , "chart2")); 
    }

}
