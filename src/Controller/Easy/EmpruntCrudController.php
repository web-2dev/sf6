<?php

namespace App\Controller\Easy;

use App\Entity\Emprunt;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EmpruntCrudController extends _CrudParentController
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

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
