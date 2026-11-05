<?php

namespace App\Form;

    use App\Entity\Exemplaire;
    use App\Entity\Livre;
    use App\Enum\Provenance;
    use App\Enum\StatutLecture;
    use App\Repository\LivreRepository;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\EnumType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\LessThanOrEqual;
    use Symfony\Component\Validator\Constraints\PositiveOrZero;

class ExemplaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('livre', EntityType::class, [
                'class' => Livre::class,
                'choice_label' => function(Livre $l) {
                    return $l->getAuteurTitre();
                },
                "query_builder" =>  function(LivreRepository $lr) { 
                    return $lr->queryAll(); 
                },
                "attr"          =>  [ "disabled" => "disabled" ]
            ])
            ->add("tome", IntegerType::class, [
                "required"      =>  false
            ])
            ->add("sousTitre", TextType::class, [
                "required"      =>  false
            ])
            ->add('acquisition', EnumType::class, [
                "class"         =>  Provenance::class,
                "choice_label"  =>  fn(Provenance $acquis) => $acquis->value
            ])

            ->add('note', ChoiceType::class, [
                    "choices"       =>  array_combine( range(0, 10), range(0, 10) ),
                    "expanded"      =>  true,
                    "multiple"      =>  false,
                    "constraints"   =>  [
                        new PositiveOrZero(message: "La plus basse note est 1"),
                        new LessThanOrEqual(value: 10, message: "La note maximale est 10")
                    ]
                ])
                ->add('commentaire')
                ->add('statut', EnumType::class, [
                    "class"         =>  StatutLecture::class,
                    "choice_label"  =>  fn(StatutLecture $statut) => $statut->label()
                ])
                // ->add('biblio', EntityType::class, [
                //     'class'         =>  Biblio::class,
                //     'choice_label'  =>  function(Biblio $b) {
                //         return $b->getLecteur()->getPseudo() . " <{$b->getNom()}>";
                //     },
                //     "attr"          =>  [ "disabled" => "disabled" ]
                // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exemplaire::class,
        ]);
    }
}
