<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Auteur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AuteurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $auteurs = [
            [ "prenom" => "Jean",       "nom" => "La Fontaine (de)",    "naissance" => "1621-07-08",    "deces" => "1695-04-13",    "biographie" => NULL ],
            [ "prenom" => "Guy",        "nom" => "Maupassant (de)",     "naissance" => "1850-08-05",    "deces" => "1893-07-06",    "biographie" => NULL ],
            [ "prenom" => "Alphonse",   "nom" => "Daudet",              "naissance" => "1840-05-13",    "deces" => "1897-12-16",    "biographie" => NULL ],
            [ "prenom" => "Alexandre",  "nom" => "Dumas",               "naissance" => "1802-07-24",    "deces" => "1870-12-05",    "biographie" => "Écrivain français, fils d'un officier né à Haïti.Ses oeuvres ont été maintes fois traduites et adaptés à la télé et au cinéma." ],      
            [ "prenom" => "Richard",    "nom" => "Matheson",            "naissance" => "1926-02-20",    "deces" => "2013-06-23",    "biographie" => NULL ],
            [ "prenom" => "J.R.R.",     "nom" => "Tolkien",             "naissance" => "1892-01-03",    "deces" => "1973-09-02",    "biographie" => NULL ],
            [ "prenom" => "Agatha",     "nom" => "Christie",            "naissance" => "1890-09-15",    "deces" => "1976-01-12",    "biographie" => "Écrivaine anglaise, célèbre pour avoir créé le personnage de détective belge, Hercule Poirot" ],      
            [ "prenom" => "Stephen",    "nom" => "King",                "naissance" => "1947-09-21",    "deces" => NULL,            "biographie" => NULL ],
            [ "prenom" => "Frank",      "nom" => "Herbert",             "naissance" => "1920-10-08",    "deces" => "1986-02-11",    "biographie" => "Climatologue de formation" ],        
            [ "prenom" => "Isaac",      "nom" => "Asimov",              "naissance" => "1920-01-02",    "deces" => "1992-04-06",    "biographie" => "Écrivain américain d'origine russe" ],         
            [ "prenom" => "Katsuhiro",  "nom" => "Otomo",               "naissance" => "1954-04-14",    "deces" => NULL,            "biographie" => "Mangaka, célèbre pour sa série Akira, devenu une référence après l'adaption au cinéma" ],      
            [ "prenom" => "Pierre",     "nom" => "Boulle",              "naissance" => "1912-02-20",    "deces" => "1994-01-31",    "biographie" => NULL ],
            [ "prenom" => "Bernard",    "nom" => "Werber",              "naissance" => "1961-09-18",    "deces" => NULL,            "biographie" => "Écrivain français" ],       
            [ "prenom" => "George",     "nom" => "Orwell",              "naissance" => "1903-06-25",    "deces" => "1950-01-21",    "biographie" => NULL ],
            [ "prenom" => "Rudyard",    "nom" => "Kipling",             "naissance" => "1865-12-30",    "deces" => "1936-01-18",    "biographie" => NULL ],
            [ "prenom" =>  NULL,        "nom" => "Homère",              "naissance" => NULL,            "deces" => NULL,            "biographie" => NULL ],
            [ "prenom" => "G.R.R.",     "nom" => "Martin",              "naissance" => "1948-09-20",    "deces" => NULL,            "biographie" => "Écrivain américain célèbre pour sa sage Game of Thrones, insipiré des Rois Maudits et de l'oeuvre de Tolkien" ],
            [ "prenom" => "Daniel",     "nom" => "Keyes",               "naissance" => "1927-08-09",    "deces" => "2014-06-15",    "biographie" => NULL ],
            [ "prenom" => "Victor",     "nom" => "Hugo",                "naissance" => "1802-02-26",    "deces" => "1885-05-22",    "biographie" => NULL ],
            [ "prenom" => "Robert",     "nom" => "Merle",               "naissance" => "1908-08-29",    "deces" => "2004-03-27",    "biographie" => NULL ],
            [ "prenom" =>  NULL,        "nom" => "Stendhal",            "naissance" => "1783-01-23",    "deces" => "1842-03-23",    "biographie" => NULL ],
            [ "prenom" => "Dan",        "nom" => "Simmons",             "naissance" => "1948-04-04",    "deces" => NULL,            "biographie" => "Né en 1948, Dan Simmons a longtemps exercé le métier d\'enseignant avant de se consacrer enttièrement à l\'écriture, publiant une dizaine de romans, dont le célèbre Hypérion. L\'échiquier du mal, considéré comme son chef-d\'oeuvre, a obtenu pratiquement tous les prix littéraires anglo-saxons en matière de fantastique (Bram Stocker Award, Locus Award, British Fantasy Award)." ],
            [ "prenom" => "Robert",     "nom" => "Heinlein",            "naissance" => "1907-07-07",    "deces" => "1988-05-08",    "biographie" => "Robert Anson Heinliein, né en 1097, écrivit en 1939 sa première nouvelle de science-fiction (Life-Line). Pour de nombreux critiques, il pass pour le\'auteur le plus important de l\'âge d\'or. Il détient snas doute le record des \"Hugos\" (érecmpoenses analogues aux \"Oscars\" du cinéma).\r\nOn l\'a souvent comparé à Rudyard Kipling pour la maîtrise de son art narratif. Dans une vaste fresque intitulée Histoire du Futur, écrite entre 1939 et 1967, il a entrepris de raconter l\'histoire des trois prochains siècles. L\'Homme qui vendit la lune est le premiere volume de cet ensemble" ],
            [ "prenom" => "Jules",      "nom" => "Verne",               "naissance" => "1828-02-08",    "deces" => "1905-03-24",    "biographie" => "Bien qu'il ait d'abord écrit des pièces de théâtre, Verne ne rencontre le succès qu'en 1863 lorsque paraît, chez l'éditeur Pierre-Jules Hetzel (1814-1886), son premier roman, Cinq Semaines en ballon. Celui-ci connaît un très grand succès, y compris à l'étranger. À partir des Aventures du capitaine Hatteras, ses romans entreront dans le cadre des Voyages extraordinaires, qui comptent 62 romans et 18 nouvelles, parfois publiés en feuilleton dans le Magasin d'éducation et de récréation, revue destinée à la jeunesse, ou dans des périodiques destinés aux adultes comme Le Temps ou le Journal des débats.             Les romans de Jules Verne, toujours très documentés, se déroulent généralement au cours de la seconde moitié du XIXe siècle. Ils prennent en compte les technologies de l'époque — Les Enfants du capitaine Grant (1868), Le Tour du monde en quatre-vingts jours (1873), Michel Strogoff (1876), L'Étoile du Sud (1884), etc. — mais aussi d'autres non encore maîtrisées ou plus fantaisistes — De la Terre à la Lune (1865), Vingt Mille Lieues sous les mers (1870), Robur-le-Conquérant (1886), etc.             Outre ses romans, on lui doit de nombreuses pièces de théâtre, des nouvelles, des récits autobiographiques, des poésies, des chansons et des études scientifiques, artistiques et littéraires. Son œuvre a connu de multiples adaptations cinématographiques et télévisuelles depuis l'origine du cinéma ainsi qu'en bande dessinée, au théâtre, à l'opéra, en musique ou en jeu vidéo.             L'œuvre de Jules Verne est universelle ; selon l’Index Translationum, avec un total de 4 751 traductions, il vient au deuxième rang des auteurs les plus traduits en langues étrangères après Agatha Christie et devant William Shakespeare. Il est ainsi, en 2011, l'auteur de langue française le plus traduit dans le monde. L'année 2005 en France a été déclarée « année Jules Verne », à l'occasion du centenaire de la mort de l'écrivain." ],
            [ "prenom" => "Brian",      "nom" => "Herbert",             "naissance" => "1947-06-29",    "deces" => NULL,            "biographie" => "fils de Frank. Il a continué la saga de son père, Dune." ],
            [ "prenom" => "Owen",       "nom" => "King",                "naissance" => "1977-02-21",    "deces" => NULL,            "biographie" => "fils de Stephen, avec qui il a co-écrit certains livres." ],
            [ "prenom" => "",           "nom" => "Molière",             "naissance" => "1622-01-15",    "deces" => "1673-02-17",    "biographie" => "Souvent considéré comme le plus grand auteur de théatre français." ],              
            [ "prenom" => "René",       "nom" => "Descartes",           "naissance" => "1596-03-31",    "deces" => "1650-02-11",    "biographie" => "René Descartes est un mathématicien, physicien et philosophe français, né le 31 mars 1596 à La Haye-en-Tourainen 1 et mort le 11 février 1650 à Stockholm. Il est considéré comme l’un des fondateurs de la philosophie moderne. Il reste célèbre pour avoir exprimé dans son Discours de la méthode le cogiton 2 — « Je pense, donc je suis » — fondant ainsi le système des sciences sur le sujet connaissant face au monde qu'il se représente. En physique, il a apporté une contribution à l’optique et est considéré comme l'un des fondateurs du mécanisme. En mathématiques, il est à l’origine de la géométrie analytique2. Certaines de ses théories ont par la suite été contestées (théorie de l’animal-machine) ou abandonnées (théorie des tourbillons ou des esprits animaux). Sa pensée a pu être rapprochée de la peinture de Nicolas Poussin3 pour son caractère clair et ordonné, rapprochement qui semble contradictoire4. Le cogito marque la naissance de la subjectivité moderne." ],       
        ];

        foreach ($auteurs as $indice => $valeur) {
            $auteur = new Auteur;
            $auteur
                ->setPrenom($valeur["prenom"])
                ->setNom($valeur["nom"])
                ->setBiographie($valeur["biographie"])
                ->setNaissance( new DateTime($valeur["naissance"]) )
                ->setDeces( new DateTime($valeur["deces"]) )
            ;
            $manager->persist($auteur);
            $str = ( $auteur->getPrenom() ? $auteur->getPrenom() . " " : "") . $auteur->getNom();
            $this->setReference( "auteur_" . $str, $auteur);

            echo "$valeur[prenom] $valeur[nom]\n";
        }
        $manager->flush();
    }
}
