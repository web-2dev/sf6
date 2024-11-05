<?php

namespace App\Controller\Easy;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\SearchMode;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

abstract class _CrudParentController extends AbstractCrudController
{

    /**
     * 💬 COURS                                                     
     * Pour afficher tous les champs (liste) sauf l'ID (formulaires)
     */
    public function configureFields(string $pageName): iterable
    {
        // Récupérer tous les champs par défaut
        $fields = parent::configureFields($pageName);

        // Supprimer l'ID par défaut (le cas échéant)
        $fields = array_filter($fields, function ($field) {
            return $field->getAsDto()->getProperty() !== 'id';
        });

        // Ajouter une nouvelle configuration pour l'ID avec hideOnForm pour les formulaires
        $idField = IdField::new('id');
        // if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $idField->hideOnForm();
        // }

        // Ajouter l'ID au début de la liste des champs
        array_unshift($fields, $idField);

        return $fields;
    }

    

    /**
     * 💬 : COURS                                                                                           
     * Permet de définir des options par défaut. Pour ajouter des définitions dans les entités,
     *      il faut appeler parent::configureCrud() au début du corps de la méthode
     */
    // public function configureCrud(Crud $crud): Crud
    // {
    //     // dump($crud);
    //     return $crud
    //         // ->renderSidebarMinimized()  // menu latéral réduit
    //         ->setPageTitle('index', 'Liste des %entity_label_plural%')  // titre h1
    //         ->setPageTitle(Crud::PAGE_DETAIL, 'Fiche %entity_label_plural%')  // titre h1
    //         ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un(e) %entity_label_plural%')  // titre h1
    //         ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un(e) %entity_label_singular%')  // titre h1

    //         ->setDateFormat('dd/MM/Y')
    //         ->setDateIntervalFormat(' %%d jours(s) %%m mois %%y année(s)')

    //         ->setThousandsSeparator('.')
    //         ->setDecimalSeparator(',')

    //         ->setAutofocusSearch()                      // 💬 place le focus automatiquement sur la recherche   
    //         ->setSearchMode(SearchMode::ALL_TERMS)      // 💬 ANY_TERMS : pour chercher au moins un mot (utiliser les " pour chercher les correspondances exactes)    
    //     ;
    // }
}
