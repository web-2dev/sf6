<?php

namespace App\Controller\Easy;

use App\Entity\Abonne;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AbonneCrudController extends _CrudParentController
{
    public static function getEntityFqcn(): string
    {
        return Abonne::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id')->hideOnForm(),
    //         TextField::new('pseudo'),
    //         TextEditorField::new('description'),
    //     ];
    // }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->setEntityLabelInSingular("Abonnés")
            ->setEntityLabelInPlural('Abonnés')
            ->setEntityPermission("ROLE_ADMIN")
        ;
    }
    
   
}
