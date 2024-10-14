<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Dev\ParentController;

class HomeController extends ParentController
{
    #[Route('/home', name: 'app_home')]
    public function index(LivreRepository $lr, Request $request): Response
    {
        /**
          * 💬 COURS : 
          * 💬 Pour générer l'affichage, on utilise la méthode render
          * 💬     1er argument   : le fichier vue que l'on veut afficher
          * 💬         le nom du fichier est donné à partir du dossier "templates"
          * 💬     2ième argument : un array qui contient les variables nécéssaires à la vue
          * 💬         Les indices de cet array correspondent aux noms des variables
          * 💬         dans le fichier twig  *   💬 
          * 💬 La fonction paginate va filter les produits à afficher selon le numéro de page demandé
          * 💬     1e argument : la liste totale des produits à afficher
          * 💬     2e argument : le numéro de la page actuelle
          * 💬     3e argument : le nombre de produits affichés par page
        */
        $page = $request->query->get("page", 1);
        $q = $request->query->get("q", 10);
        $livres = $lr->findAllPaginate(page: $page, count: $q);
        
        return $this->render('home/index.html.twig', [
            'liste' => $livres,
        ]);
    }
}
