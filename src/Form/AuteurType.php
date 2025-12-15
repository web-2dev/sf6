<?php

namespace App\Form;

use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AuteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                "constraints"           => [
                                                new Length(min: 2, max: 30, minMessage: "2 caractères minimume", maxMessage: "{size} caractères maximum")
                                           ]
            ])
            ->add('prenom', null, [
                "constraints"           => [
                                                new Length(min: 2, max: 30, minMessage: "2 caractères minimume", maxMessage: "{size} caractères maximum")
                                           ]
            ])
            ->add('biographie')
            ->add('naissance', null, [
                'widget' => 'single_text',
            ])
            ->add('deces', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
