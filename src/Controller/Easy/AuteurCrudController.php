<?php

namespace App\Controller\Easy;

use App\Entity\Auteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Validator\Constraints\Image;

class AuteurCrudController extends _CrudParentController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = parent::configureFields($pageName);
        $fields[] = ImageField::new("portrait", "Portrait")
                              ->setBasePath("/img/portraits/")
                              ->setUploadDir("/public/img/portraits/") // ⚠ le dossier doit exister 
                              ->setFileConstraints([
                                new Image(maxSize: "2M", mimeTypes: ["image/jpeg", "image/png"],
                                          maxSizeMessage: "Taille maximum du fichier : 2 Mo", 
                                          mimeTypesMessage: "Formats autorisés : jpeg, png")
                              ]);
        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->setDefaultSort([ 'nom' => 'ASC', 'prenom' => 'ASC'])
            ->setSearchFields(["nom", "prenom", "biographie"])
            ->setPaginatorPageSize(4)
            ->setPaginatorRangeSize(1)
        ;
    }
    
}
