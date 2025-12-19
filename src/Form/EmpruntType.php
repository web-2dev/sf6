<?php

namespace App\Form;

use App\Entity\Abonne;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Repository\AbonneRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $emprunt = $options["data"];
        $builder

            ->add('livre', EntityType::class, [
                "class"         => Livre::class,
                "choice_label"  => "titreAuteur", 
                "placeholder"   =>  "choisir le livre emprunté..."
            ])
            ->add('abonne', EntityType::class, [
                "class"         =>  Abonne::class,
                "query_builder" =>  function(AbonneRepository $ar) use ($emprunt) { 
                    if( $emprunt->getAbonne() ) {
                        return $ar->createQueryBuilder("a")->where("a.id = :id")->setParameter("id", $emprunt->getAbonne()->getId());
                    }
                    return $ar->queryBuilderfindLecteurs(); 
                },
                "choice_label"  =>  function(Abonne $a) {
                    return "{$a->getPseudo()} ({$a->getIdentite()})";
                }, 
                "placeholder"   => $emprunt->getAbonne() ? false : "choisir l'abonné emprunteur...",
                "attr"          =>  $emprunt->getAbonne() ? ["readonly" => "readonly" ] : []
            ])
            ->add('dateEmprunt', null, [
                'widget' => 'single_text',
                "label"         =>  "Emprunté le"
            ])
        ;
        if( $emprunt->getId() ) {
            $builder
                ->add('datePrevue', null, [
                    'widget'    => 'single_text',
                    "mapped"    => (bool)$emprunt->getId(),
                    "label"     =>  "Retour prévue le"
                ])
                ->add('dateRetour', null, [
                    'widget' => 'single_text',
                    "label"         =>  "Rendu le",
                    "required"      =>  false
                ]);
        } else {
            $builder
                ->add('datePrevue', null, [
                    'widget'    =>  'single_text',
                    "mapped"    =>  (bool)$emprunt->getId(),
                    "attr"      =>  ["disabled" => "disabled"],
                    "label"     =>  "Retour prévue le"
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
