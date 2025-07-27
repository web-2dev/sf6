<?php

namespace App\Controller\Easy;

use App\Entity\Auteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Validator\Constraints\Image;

class AuteurCrudController extends _CrudParentController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }


    public function configureFields(string $pageName): iterable
    {
        // nom, prenom, biographie, naissance, deces, portrait
        // $fields = parent::configureFields($pageName);
        // $fields = array_filter($fields, function ($field) {
        //     return !in_array($field->getAsDto()->getProperty(), ['portrait']);
        // });

        if( $pageName != Crud::PAGE_INDEX ) {
            $fields[] = FormField::addColumn(6);
            $fields[] = FormField::addPanel('Identité')->setIcon('fa fa-id-card')->setCssClass("panel-primary");

            $fields[] = ImageField::new("portrait", "Portrait")
                ->setBasePath("/img/portraits/")
                ->setUploadDir("/public/img/portraits/") // ⚠ le dossier doit exister 
                ->setFileConstraints([
                    new Image(
                        maxSize: "2M",
                        mimeTypes: ["image/jpeg", "image/png"],
                        maxSizeMessage: "Taille maximum du fichier : 2 Mo",
                        mimeTypesMessage: "Formats autorisés : jpeg, png"
                    )
                ]);
            $fields[] = TextField::new("prenom", "Prénom");
            $fields[] = TextField::new("nom", "Nom");

            //
            $fields[] = FormField::addColumn(6);
            $fields[] = FormField::addPanel('Informations')->setIcon('fa fa-fingerprint');

            $fields[] = DateField::new("naissance", "Date de naissance")->setColumns(6)->setCssClass("w-100");
            $fields[] = DateField::new("deces", "Date de décès")->setColumns(6)->setCssClass("w-100");
            $fields[] = TextEditorField::new("biographie", "Biographie");
        } else {
            $fields[] = IntegerField::new("id", "Identifiant");
            $fields[] = ImageField::new("portrait", "Portrait")->setBasePath("/img/portraits/");
            $fields[] = TextField::new("identite", "Nom");
            $fields[] = DateField::new("naissance", "Naissance");
            $fields[] = DateField::new("deces", "Décès");
            
        }
        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->setDefaultSort(['nom' => 'ASC', 'prenom' => 'ASC'])
            ->setSearchFields(["nom", "prenom", "biographie"])
            ->setPaginatorPageSize(50)
            ->setPaginatorRangeSize(1)
        ;
    }
}
