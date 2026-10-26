<?php

namespace App\Controller\Dev;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Entity\Abonne as Lecteur;
use Symfony\Component\HttpFoundation\Request;

abstract class ParentController extends AbstractController
{
    public AsciiSlugger $slugger;
    public Lecteur $lecteur;
    public string $col;
    public string $dir;


    public function __construct() {
        $this->slugger = new AsciiSlugger();
    }

    public function setSortColumn(Request $request){
        $this->col = $request->query->get("col", "titre");
        $this->dir = $request->query->get("dir", "asc");
    }

    /**
     * ! COURS
     * En surchargeant la méthode render, on permet de nommer les fichier vues sans l'extension '.html.twig'
     */
    public function render($view, $viewParameters = [], ?Response $response = null) : Response {
        $view = $view . (strpos($view, ".html.twig") == false ? ".html.twig" : "");
        return parent::render($view, $viewParameters, $response);
    }

}
