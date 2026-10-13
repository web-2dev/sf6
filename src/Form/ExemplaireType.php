<?php

namespace App\Form;

use App\Entity\Biblio;
use App\Entity\Exemplaire;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExemplaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('commentaire')
            ->add('statut')
            ->add('acquisition')
            ->add('creation', null, [
                'widget' => 'single_text',
            ])
            ->add('modification', null, [
                'widget' => 'single_text',
            ])
            ->add('suppression', null, [
                'widget' => 'single_text',
            ])
            ->add('biblio', EntityType::class, [
                'class' => Biblio::class,
                'choice_label' => 'id',
            ])
            ->add('livre', EntityType::class, [
                'class' => Livre::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exemplaire::class,
        ]);
    }
}
