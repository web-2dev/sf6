<?php

namespace App\Controller\Easy;

use App\Entity\Emprunt;
use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EmpruntCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Emprunt::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        $crud
            ->setSearchFields(null)     // 💬 cache la barre de recherche   
        ;
        return $crud;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = parent::configureFields($pageName);
        $fields[] = TextField::new("abonne.identite", "Abonné")->onlyOnDetail();
        /** 
         * 💬 COURS  
         * AssociationFied permet d'afficher un select (relation 1-n)
         *      setCrudController : choix de l'entité liée 
         * NB: l'affichage se fait avec la méthode __toString de l'entité   
         * */
        $fields[] = AssociationField::new("abonne", "Lecteur")
                        ->setCrudController("App\Controller\Easy\AbonneCrudController")
                        ->setColumns(6)  // class="col-6"
                        ->setCustomOptions(["choice_label" => "pseudo"])
        ;

        $fields[] = AssociationField::new("livre", "Livre")
                        ->setCrudController(LivreCrudController::class)
                        ->setColumns(6)
                        ->autocomplete();
        return $fields;
    }
   
}
