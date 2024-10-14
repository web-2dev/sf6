<?php

namespace App\Controller\Dev;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

abstract class ParentController extends AbstractController
{
    /**
     * ! COURS
     * En surchargeant la méthode render, on permet de nommer les fichier vues sans l'extension '.html.twig'
     */
    public function render($view, $viewParameters = [], Response $response = null) : Response {
        $view = $view . (strpos($view, ".html.twig") == false ? ".html.twig" : "");
        return parent::render($view, $viewParameters, $response);
    }

}
