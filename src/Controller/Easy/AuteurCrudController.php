<?php

namespace App\Controller\Easy;

use App\Entity\Auteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AuteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id')->hideOnForm(),
    //         TextField::new("prenom"),
    //         TextField::new('nom'),
    //         DateField::new('naissance'),
    //         DateField::new('deces'),
    //         TextEditorField::new('biographie'),
    //     ];
    // }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->setDefaultSort([ 'nom' => 'ASC', 'prenom' => 'ASC'])
        ;
    }
    
}
