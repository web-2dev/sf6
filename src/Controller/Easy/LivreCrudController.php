<?php

namespace App\Controller\Easy;

use App\Entity\Livre;
use App\Controller\Easy\GenreCrudController;
use App\Controller\Easy\AuteurCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\DomCrawler\Field\FileFormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class LivreCrudController extends _CrudParentController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // $fields = parent::configureFields($pageName);
        // $fields = array_filter($fields, function ($field) {
        //     return $field->getAsDto()->getProperty() !== 'couverture';
        // });

        //! Champs absents du formulaire
        $fields[] = TextField::new("auteur.identite", "Auteur")->hideOnForm();


        $fields[] = FormField::addColumn(6);
        $fields[] = TextField::new("titre", "Titre du livre");
        $fields[] = ImageField::new("couverture", "Couverture")
            ->setBasePath("/img/couvertures/")
            ->setUploadDir("/public/img/couvertures/")
            ->setFileConstraints([
                new Image(
                    maxSize: "2M",
                    mimeTypes: ["image/jpeg", "image/png"],
                    maxSizeMessage: "Taille maximum du fichier : 2 Mo",
                    mimeTypesMessage: "Formats autorisés : jpeg, png"
                )
            ]);
        $fields[] = AssociationField::new("genres", "Genres")
            ->setCrudController(GenreCrudController::class)
            ->setCustomOptions([
                "multiple" => true,
                "expanded" => false,
            ])
            ->onlyOnForms();


        $fields[] = FormField::addColumn(6);
        $fields[] = AssociationField::new("auteur", "Auteur")
                        ->onlyOnForms()
                        ->setCrudController(AuteurCrudController::class)
                        ->setCustomOptions(["choice_label" => "titre"])
                        ->setFormTypeOption('placeholder', 'Choisir un auteur...')
        ;
        $fields[] = TextEditorField::new("synopsis", "4ième de couverture");

        $fields[] = FormField::addColumn(12);
        $fields[] = TextField::new("url", "Chemin URL")->onlyOnForms()
                        ->setRequired(false)
                        ->setHelp("Défini automatiquement si vide");

        // ! Champs absents du formulaire
        $fields[] = TextField::new("genre", "Genres")->hideOnForm();

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);

        $crud
            ->setSearchFields(['titre', 'synopsis', "genres.libelle"])   // 💬 On peut rechercher dans les tables associées
            ->setDefaultSort(['auteur.nom' => 'ASC', 'titre' => 'ASC'])
            ->setPaginatorPageSize(50)
            ->setPaginatorRangeSize(1)
        ;


        return $crud;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add("titre")->add("auteur");
    }
}
