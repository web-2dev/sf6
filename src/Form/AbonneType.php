<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('roles', ChoiceType::class, [
                "choices"   => [
                    "Utilisateur"    => "ROLE_USER",
                    "Lecteur"        => "ROLE_LECTEUR",
                    "Bibliothécaire" => "ROLE_BIBLIOTHECAIRE",
                    "Webmaster"      => "ROLE_ADMIN",
                    "Développeur"    => "ROLE_DEV",
                ],
                "multiple"  => true,
                "expanded"  => true,
                "label"     => "Niveau d'accès"
                            
            ])
            ->add('password')
            ->add('prenom')
            ->add('nom')
            ->add('adresse')
            ->add('naissance', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
