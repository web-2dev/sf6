<?php

namespace App\Form;

use App\Entity\Abonne;
use App\Entity\Critique;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CritiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('commentaire')
            ->add('abonne', EntityType::class, [
                'class' => Abonne::class,
                'choice_label' => 'identite',
            ])
            ->add('livre', EntityType::class, [
                'class' => Livre::class,
                'choice_label' => 'titreauteur',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Critique::class,
        ]);
    }
}
