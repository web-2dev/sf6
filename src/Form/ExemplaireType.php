<?php

namespace App\Form;

use App\Entity\Biblio;
use App\Entity\Exemplaire;
use App\Entity\Livre;
use App\Enum\StatutLecture;
use App\Repository\LivreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExemplaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('commentaire')
            ->add('statut', EnumType::class, [
                "class"     =>  StatutLecture::class
            ])
            ->add('acquisition')
            // ->add('creation', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('modification', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('suppression', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('biblio', EntityType::class, [
                'class' => Biblio::class,
                'choice_label' => function(Biblio $b) {
                    return $b->getLecteur()->getPseudo() . " <{$b->getNom()}>";
                },
            ])
            ->add('livre', EntityType::class, [
                'class' => Livre::class,
                'choice_label' => function(Livre $l) {
                    return $l->getAuteurTitre();
                },
                "query_builder" =>  function(LivreRepository $lr) { 
                    return $lr->queryAll(); 
                },
            ])
            ->add("tome", IntegerType::class, [
                "required"      =>  false
            ])
            ->add("sousTitre", TextType::class, [
                "required"      =>  false
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
