<?php

namespace App\Form;

use App\Entity\Auteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuteurCrudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('auteurChoice', ChoiceType::class, [
                "label"         =>  "Options",
                "choices"       =>  [ "Auteur existant" => "old", "Nouvel auteur" => "new" ],
                "expanded"      =>  true,
                "mapped"        =>  false,
                "data"          =>  "old",
            ])

            ->add("auteurExistant", EntityType::class, [
                "class"         =>  Auteur::class,
                "choice_label"  =>  "identite",
                "placeholder"   =>  "...",
                "required"      =>  false,
                "mapped"        =>  false,
                "label"         =>  "Choisir un auteur"
            ])
            
            ->add("nouvelAuteur", AuteurLivreType::class, [
                "label"         =>  "Nouvel auteur",
                "required"      =>  false,
                "mapped"        =>  false,
            ])
        ;
        // 💬 Ajouter un listener pour gérer la logique conditionnelle  
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, "onPreSubmit"]);
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, "onPostSubmit"]);
    }

    //EventListeners
    public function onPreSubmit(FormEvent $submitEvent) : void {
        $data = $submitEvent->getData();
        $form = $submitEvent->getForm();
        if( isset($data["auteurChoice"]) ) {
            if( $data["auteurChoice"] == "old" ) {
                $form->get("auteurExistant")->setData(null);
            } else {
                $form->get("nouvelAuteur")->setData(new Auteur());
            }
        }
    }
    
    public function onPostSubmit(FormEvent $submitEvent) : void {
        $data = $submitEvent->getData();
        $form = $submitEvent->getForm();
        $choice = $form->get("auteurChoice")->getData();
        if( $choice == "new" ) {
            $auteur = $form->get("nouvelAuteur")->getData();
        } else {
            $auteur = $form->get("auteurExistant")->getData();
        }
        $submitEvent->setData( $auteur ); 
    }
    


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
