<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\AsciiSlugger;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $livres = [
            [ "titre" => "Dune",                            "auteur" => "Frank Herbert",        "couverture" => "dune.jpg" ],
            [ "titre" => "1984",                            "auteur" => "George Orwell",        "couverture" => "1984.jpg", "synopsis" => "Un monde dystopique où tout le monde est surveillé par Big Brother et où les enfants dénoncent leurs parents" ],
            [ "titre" => "I, robot",                        "auteur" => "Isaac Asimov",         "couverture" => "i_robot.jpg" ],
            [ "titre" => "Le Seigneur des Anneaux",         "auteur" => "J.R.R. Tolkien",       "couverture" => "le_seigneur_des_anneaux.jpg" ],
            [ "titre" => "Les Deux Tours",                  "auteur" => "J.R.R. Tolkien",       "couverture" => "les_deux_tours.jpg" ],
            [ "titre" => "A.B.C. contre Poirot",            "auteur" => "Agatha Christie",      "couverture" => "abc_contre_poirot.jpg" ],
            [ "titre" => "Fondation",                       "auteur" => "Isaac Asimov",         "couverture" => "fondation.jpg" ],
            [ "titre" => "Fondation et Empire",             "auteur" => "Isaac Asimov",         "couverture" => "fondation_et_empire.jpg" ],
            [ "titre" => "Je suis une légende",             "auteur" => "Richard Matheson",     "couverture" => "je_suis_une_legende.jpg" ],
            [ "titre" => "Les fourmis",                     "auteur" => "Bernard Werber",       "couverture" => "les_fourmis.jpg" ],
            [ "titre" => "Fondation foudroyée",             "auteur" => "Isaac Asimov",         "couverture" => "fondation_foudroyee.jpg" ],
            [ "titre" => "Les Trois mousquetaires",         "auteur" => "Alexandre Dumas",      "couverture" => "les_trois_mousquetaires.jpg" ],
            [ "titre" => "Le jour des fourmis",             "auteur" => "Bernard Werber",       "couverture" => "le_jour_des_fourmis.jpg" ],
            [ "titre" => "Le retour d'Hercule Poirot",      "auteur" => "Agatha Christie",      "couverture" => "le_retour_d_hercule_poirot.jpg" ],
            [ "titre" => "L'avare",                         "auteur" => "Molière",              "couverture" => "l_avare.jpg" ],
            [ "titre" => "Discours de la méthode",          "auteur" => "René Descartes",       "couverture" => "discours_de_la_methode.jpg" ],
            [ "titre" => "Akira tome 1",                    "auteur" => "Katsuhiro Otomo",      "couverture" => "akira_1.jpg", "synopsis" => "Une bande de motards dans la ville de Neo-Tokyo en 2010" ],
            [ "titre" => "Odyssée",                         "auteur" => "Homère",               "couverture" => "l_univers_de_la_mythologie_grecque.jpg", "synopsis" => "c'est le nom grec d'Ulysse" ],
            [ "titre" => "Le Trône de fer",                 "auteur" => "G.R.R. Martin",        "couverture" => "le_trone_de_fer.jpg" ],
            [ "titre" => "Le crime de l'Orient-Express",    "auteur" => "Agatha Christie",      "couverture" => "le_crime_de_l_orient-express.jpg" ],
            [ "titre" => "Des fleurs pour Algernon",        "auteur" => "Daniel Keyes",         "couverture" => "des_fleurs_pour_algernon.jpg", "synopsis" => "Algernon, c'est un rat" ],
            [ "titre" => "Les Trois Mousquetaires",         "auteur" => "Alexandre Dumas",      "couverture" => "les_trois_mousquetaires.jpg", "synopsis" => "En fait, ils sont 4..." ],
            [ "titre" => "Hercule Poirot",                  "auteur" => "Agatha Christie",      "couverture" => "hercule_poirot.jpg", "synopsis" => "" ],
            [ "titre" => "Le Horla",                        "auteur" => "Guy Maupassant (de)",  "couverture" => "le_horla.jpg", "synopsis" => "" ],
            [ "titre" => "Shining",                         "auteur" => "Stephen King",         "couverture" => "shining.jpg", "synopsis" => "" ],
            [ "titre" => "Le  Seigneur des Anneaux",        "auteur" => "J.R.R. Tolkien",       "couverture" => "le_seigneur_des_anneaux.jpg", "synopsis" => "" ],
            [ "titre" => "Le Messie de Dune",               "auteur" => "Frank Herbert",        "couverture" => "le_messie_de_dune.jpg", "synopsis" => "" ],
            [ "titre" => "Les Enfants de Dune",             "auteur" => "Frank Herbert",        "couverture" => "les_enfants_de_dune.jpg", "synopsis" => "" ],
            [ "titre" => "Le Retour du Roi",                "auteur" => "J.R.R. Tolkien",       "couverture" => "le_retour_du_roi.jpg", "synopsis" => "" ],
            [ "titre" => "Le Livre de la Jungle",           "auteur" => "Rudyard Kipling",      "couverture" => "le_livre_de_la_jungle.jpg", "synopsis" => "" ],
            [ "titre" => "Les Misérables",                  "auteur" => "Victor Hugo",          "couverture" => "les_misérables.jpg", "synopsis" => "" ],
            [ "titre" => "Malevil",                         "auteur" => "Robert Merle",         "couverture" => "malevil.jpg", "synopsis" => "" ],
            [ "titre" => "Futurs en délire",                "auteur" => "Isaac Asimov",         "couverture" => "futurs_en_délire.jpg", "synopsis" => "" ],
            [ "titre" => "L'échiquier du mal 1",            "auteur" => "Dan Simmons",          "couverture" => "l_échiquier_du_mal_1.jpg", "synopsis" => "" ],
            [ "titre" => "Seconde Fondation",               "auteur" => "Isaac Asimov",         "couverture" => "seconde_fondation.jpg", "synopsis" => "" ],
            [ "titre" => "L'aube de Fondation",             "auteur" => "Isaac Asimov",         "couverture" => "l_aube_de_fondation.jpg", "synopsis" => "" ],
            [ "titre" => "Prélude à Fondation",             "auteur" => "Isaac Asimov",         "couverture" => "prélude_à_fondation.jpg", "synopsis" => "" ],
            [ "titre" => "Deux ans de vacances",            "auteur" => "Jules Verne",          "couverture" => "deux_ans_de_vacances.jpg", "synopsis" => "Préface de l'auteur : \r\nBien des Robinsons ont déjà tenu en éveil nos jeunes lecteurs." ],
            [ "titre" => "L'homme qui vendit la Lune",      "auteur" => "Jules Verne",          "couverture" => "l_homme_qui_vendit_la_lune.jpg", "synopsis" => "" ],
            [ "titre" => "Carrie",                          "auteur" => "Stephen King",         "couverture" => "carrie.jpg", "synopsis" => "Carrie a des pouvoirs psychiques refoulés" ],
            [ "titre" => "Ça",                              "auteur" => "Stephen King",         "couverture" => "ça.jpg", "synopsis" => "L'histoire d'un clown qui s'ennuie tout seul, en bas !" ],
            
        ];
        
        $slugger = new AsciiSlugger();
        foreach ($livres as $cpt => $book) {
            $livre = new Livre;
            $livre
                ->setTitre( $book["titre"] )
                ->setAuteur( $this->getReference("auteur_" . $book["auteur"]) )
                ->setCouverture( $book["couverture"] )
                ->setUrl( $slugger->slug($book["titre"]) );
            $manager->persist( $livre );

            // Une référence pour cibler un livre en particulier
            $this->setReference( "livre_" . $book["titre"], $livre );
            
            // Une référence pour choisr un livre aléatoirement
            echo "livre_$cpt : $book[titre]\n";
            $this->setReference( "livre_$cpt", $livre );  // 42 livres

        }

        $manager->flush();
    }
}
