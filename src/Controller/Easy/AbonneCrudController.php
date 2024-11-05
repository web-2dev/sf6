<?php

namespace App\Controller\Easy;

use App\Entity\Abonne;
use App\Repository\AbonneRepository;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AbonneCrudController extends AbstractCrudController
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
            ->setEntityLabelInSingular("Abonné")
            ->setEntityLabelInPlural('Abonnés')
            ->setEntityPermission("ROLE_ADMIN")
        ;
    }


    #[Route(path: "/easy/lecteurs", name: 'app_easy_admin_lecteurs', methods: ['GET'])]
    public function lecteurs(AbonneRepository $ar) {
        {
        return $this->render('admin/abonne/index.html.twig', [
            'abonnes' => $ar->findLecteurs(),
        ]);
    }
}
   
}
