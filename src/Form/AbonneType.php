<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class AbonneType extends AbstractType
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [  "Directeur"         => "ROLE_ADMIN",
                    "Bibliothécaire"    => "ROLE_BIBLIO",
                    "Lecteur"           => "ROLE_LECTEUR",
                    "Abonné"            => "ROLE_USER" ,
                 ];
        if($this->security->isGranted("ROLE_DEV")){
            $roles["Développeur"] = "DEV";
        }
        $abonne = $options["data"];

        $builder
            ->add('pseudo')
            ->add('password', TextType::class, [
                "mapped"        =>  false,
                "required"      =>  !$abonne->getId(),
                "label"         =>  "Mot de passe",
                // "constraints"   =>  [
                //                         new Regex(pattern: "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{6,15})$/",
                //                                   message: "Le mot de passe doit être composé d'au moins :
                //                                                 une minuscule,
                //                                                 une majuscule,
                //                                                 un chiffre,
                //                                                 un caractère spécial -+!*$@%_,
                //                                                 6 caractères, jusqu'à 15")
                //                     ]
            ])
            ->add('roles', ChoiceType::class, [
                "choices"       =>  $roles,
                "multiple"      =>  true,
                "expanded"      =>  true,
                "label"         =>  "Autorisations",
                "attr"      =>  ["class" => "d-flex"]
            ],
            )
            ->add('prenom', null, [ "label" => "Prénom" ])
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
