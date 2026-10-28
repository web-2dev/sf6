<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Repository\AuteurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('synopsis')
            ->add('couverture', FileType::class, [
                "mapped"        =>  false,
                "required"      =>  false,
                "constraints"   =>  [
                                        new Image(
                                                mimeTypes: ["image/*"], mimeTypesMessage: "Ce type de fichier n'est pas accepté",
                                                allowLandscape: false, allowLandscapeMessage: "Le format paysage n'est pas accepté")
                                    ]

            ])
            // ->add('url')
            ->add('auteur', EntityType::class, [
                'class'         =>  Auteur::class,
                'choice_label'  =>  fn(Auteur $a) => $a->getId() . " - " . $a->getIdentite(),
                "query_builder" =>  fn(AuteurRepository $repoAuteur) => $repoAuteur->createQueryBuilder("a")
                                        ->orderBy("a.prenom", "asc")->addOrderBy("a.nom", "asc"),
                "placeholder"   =>  "",
            ])
            ->add('genres', EntityType::class, [
                'class'         => Genre::class,
                'choice_label'  => 'libelle',
                'multiple'      => true,
                "expanded"      =>  true,
                "attr"      =>  [ "class" => "d-flex flex-wrap gap-4", "style"=>"font-size: 1.2em;" ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
