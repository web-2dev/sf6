<?php

namespace App\Form;

use App\Entity\Abonne;
use App\Entity\Biblio;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BiblioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('creation', null, [
                'widget' => 'single_text',
            ])
            ->add('modification', null, [
                'widget' => 'single_text',
            ])
            ->add('suppression', null, [
                'widget' => 'single_text',
            ])
            ->add('lecteur', EntityType::class, [
                'class' => Abonne::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Biblio::class,
        ]);
    }
}
