<?php

namespace App\Controller\Easy;

use App\Entity\Genre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GenreCrudController extends _CrudParentController
{
    public static function getEntityFqcn(): string
    {
        return Genre::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return parent::configureFields($pageName);
    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->setDefaultSort(['libelle' => 'ASC'])
            ->setSearchFields(["libelle", "mots_cles", "presentation"])
            ->setPaginatorPageSize(50)
            ->setPaginatorRangeSize(1)
        ;
    }

   
}
