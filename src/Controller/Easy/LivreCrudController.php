<?php

namespace App\Controller\Easy;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LivreCrudController extends _CrudParentController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = parent::configureFields($pageName);
        $fields[] = TextField::new("auteur.identite", "Auteur");
        return $fields;

    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);

        $crud
            ->setSearchFields(['titre', 'synopsis', "genres.libelle"])   // 💬 On peut rechercher dans les tables associées
            ->setDefaultSort([ 'auteur.nom' => 'ASC', 'titre' => 'ASC'])
        ;


        return $crud;
    }
   
}
