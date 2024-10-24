<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\DataFixtures\LivreFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cat01 = new Genre();
        $cat01
            ->setLibelle("science-fiction")
            ->setMotsCles("futur, robot, androïde, avenir, espace, anticipation")
            ->setPresentation("science mais dans une fiction... donc ce n'est plus vraiment de la science 🤪")
            ->addLivre($this->getReference("livre_Dune"))
            ->addLivre($this->getReference("livre_Le Messie de Dune"))
            ->addLivre($this->getReference("livre_Les Enfants de Dune"))
            ->addLivre($this->getReference("livre_1984"))
            ->addLivre($this->getReference("livre_I, robot"))
            ->addLivre($this->getReference("livre_Fondation"))
            ->addLivre($this->getReference("livre_Seconde Fondation"))
            ->addLivre($this->getReference("livre_L'aube de Fondation"))
            ->addLivre($this->getReference("livre_Prélude à Fondation"))
            ->addLivre($this->getReference("livre_Fondation et Empire"))
            ->addLivre($this->getReference("livre_Fondation foudroyée"))
            ->addLivre($this->getReference("livre_Futurs en délire"))
            ->addLivre($this->getReference("livre_L'échiquier du mal 1"))
            ->addLivre($this->getReference("livre_Des fleurs pour Algernon"))
            ->addLivre($this->getReference("livre_L'homme qui vendit la Lune"))
        ;

        $cat02 = new Genre;
        $cat02
            ->setLibelle("fantasy")
            ->setMotsCles("dragon, chevalier, moyen-âge, magie, heroic, médieval, créature, dark")
            ->setPresentation("La fantasy se caractérise par l'existence d'un monde imaginaire où des éléments surnaturels sont intégrés à la normalité. Les créatures mythiques (dragons, elfes, sorciers) et la magie y sont souvent omniprésentes. Les univers de fantasy obéissent à leurs propres lois et cohérences internes.")
            ->addLivre($this->getReference("livre_Le Seigneur des Anneaux"))
            ->addLivre($this->getReference("livre_Les Deux Tours"))
            ->addLivre($this->getReference("livre_Le Retour du Roi"))
            ->addLivre($this->getReference("livre_Le Trône de fer"))
            ->addLivre($this->getReference("livre_Dune"))
            ->addLivre($this->getReference("livre_Le Messie de Dune"))
            ->addLivre($this->getReference("livre_Les Enfants de Dune"))
            ->addLivre($this->getReference("livre_Fondation"))
            ->addLivre($this->getReference("livre_Fondation et Empire"))
            ->addLivre($this->getReference("livre_L'échiquier du mal 1"))
        ;

        $cat03 = new Genre;
        $cat03->setLibelle("policier")->setMotsCles("enquête, détective, meutre, investigation, noir, espionnage, meurtre");
        $cat03->addLivre($this->getReference("livre_A.B.C. contre Poirot"))
            ->addLivre($this->getReference("livre_Hercule Poirot"))
            ->addLivre($this->getReference("livre_Le retour d'Hercule Poirot"))
            ->addLivre($this->getReference("livre_Le crime de l'Orient-Express"));

        $cat04 = new Genre;
        $cat04->setLibelle("théatre")->setMotsCles("comédie, drame");
        $cat04->addLivre($this->getReference("livre_L'avare"));

        $cat05 = new Genre;
        $cat05
            ->setLibelle("littérature")->setMotsCles("classique")
            ->addLivre($this->getReference("livre_Les Trois mousquetaires"))
            ->addLivre($this->getReference("livre_Les Misérables"))
            ->addLivre($this->getReference("livre_Malevil"))
            ->addLivre($this->getReference("livre_Deux ans de vacances"))
        ;

        $cat06 = new Genre;
        $cat06
            ->setLibelle("fantastique")
            ->setMotsCles("étrange, merveilleux, bizarre")
            ->setPresentation("Le fantastique joue avec l'irruption de l'étrange dans le monde réel. Dans ce genre, le surnaturel survient sans explication rationnelle, créant ainsi une ambiguïté. Les personnages doutent souvent de ce qu'ils voient, et l'inexplicable est en tension avec la réalité quotidienne.")
            ->addLivre($this->getReference("livre_Le Horla"))
            ->addLivre($this->getReference("livre_Shining"))
            ->addLivre($this->getReference("livre_Je suis une légende"))
            ->addLivre($this->getReference("livre_Les fourmis"))
            ->addLivre($this->getReference("livre_Le jour des fourmis"))
            ->addLivre($this->getReference("livre_Le Livre de la Jungle"))
            ->addLivre($this->getReference("livre_Carrie"))
        ;

        $cat07 = new Genre;
        $cat07->setLibelle("philosophie")->setMotsCles("scepticisme, stoïcisme, dialectique, métaphysique");
        $cat07->addLivre($this->getReference("livre_Discours de la méthode"));

        $cat08 = new Genre;
        $cat08->setLibelle("manga")->setMotsCles("dessin, japon, épique, arts-martiaux");
        $cat08->addLivre($this->getReference("livre_Akira tome 1"));

        $cat09 = new Genre;
        $cat09->setLibelle("mythologie")->setMotsCles("dieu, déesse, titan, arts-martiaux");
        $cat09->addLivre($this->getReference("livre_Odyssée"));


        $cat10 = new Genre;
        $cat10
            ->setLibelle("épouvante")
            ->setMotsCles("horreur, gore")
            ->addLivre($this->getReference("livre_Shining"))
            ->addLivre($this->getReference("livre_Ça"))
        ;

        $cat11 = new Genre;
        $cat11->setLibelle("romantique")->setMotsCles("eau de rose, amour, passion");

        $cat11 = new Genre;
        $cat11
            ->setLibelle("aventure")
            ->setMotsCles("voyage, action, romanesque, trésor")
            ->addLivre($this->getReference("livre_Les Trois mousquetaires"))
            ->addLivre($this->getReference("livre_Deux ans de vacances"))
        ;

        $cat12 = new Genre;
        $cat12
            ->setLibelle("fable")
            ->setMotsCles("conte, fée, conte de fée, morale")
            ->addLivre($this->getReference("livre_Le Livre de la Jungle"))
        ;

        $cat13 = new Genre;
        $cat13->setLibelle("poésie")->setMotsCles("poème, ver, alexandrin, hémistiche, prose, onirique");

        for ($i=1; $i < 14; $i++) { 
            $cat = "cat" . str_pad($i, 2, 0, STR_PAD_LEFT);
            echo $cat . " : ";
            $manager->persist($$cat);
            // print_r($$cat);
        }
        $manager->flush();
    }

    /**
     * COURS : C'est dans cette méthode que l'on définie les fixtures qui doivent être exécutées avant
     */
    public function getDependencies()
    {
        return [LivreFixtures::class];
    }
}
