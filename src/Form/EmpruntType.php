<?php

namespace App\Form;

use App\Entity\Abonne;
use App\Entity\Emprunt;
use App\Entity\Livre;
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
            ->add('dateEmprunt', null, [
                'widget' => 'single_text',
                "label"         =>  "Emprunté le"
            ])
            ->add('datePrevue', null, [
                'widget'    => 'single_text',
                "required"  => false,
                "mapped"    => false       
            ])

            ->add('livre', EntityType::class, [
                "class"         => Livre::class,
                "choice_label"  => "titre", 
                "placeholder"   =>  "choisir le livre emprunté..."
            ])
            ->add('abonne', EntityType::class, [
                "class"         => Abonne::class,
                "choice_label"  => "pseudo", 
                "placeholder"   =>  "choisir l'abonné emprunteur...",
                "attr"          =>  $emprunt->getAbonne() ? ["disabled" => "disabled" ] : []
            ])

        ;
        if( $emprunt->getId() ) {
            $builder->add('dateRetour', null, [
                'widget' => 'single_text',
                "label"         =>  "Rendu le",
                "required"      =>  false
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
