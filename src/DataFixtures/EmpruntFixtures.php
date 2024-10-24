<?php

namespace App\DataFixtures;

use DateTime;
use DateInterval;
use App\Entity\Emprunt;
use App\DataFixtures\LivreFixtures;
use App\DataFixtures\AbonneFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EmpruntFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // 10 lecteurs, 42 livres
        for ($i=0; $i < 31; $i++) { 
            $lecteur = "abonne_" . rand(0, 9);
            $livre   = "livre_" . rand(0, 40);
            $debut   = rand(2020, 2024) . "-" . rand(1, 12) . "-" . rand(1, 31);
            $sortie  = new DateTime( $debut );
            $retour  = clone $sortie;
            $delai = rand(0, 30);
            $retour  = $delai ? $retour->add(new DateInterval("P" . $delai . "D")) : null;

            $emprunt = new Emprunt;
            $emprunt->setDateEmprunt( $sortie );
            $emprunt->setDateRetour( $retour );
            $emprunt->setDatePrevue( $sortie->add(new DateInterval("P2W")) );
            $emprunt->setAbonne( $this->getReference( $lecteur ) );
            $emprunt->setLivre( $this->getReference( $livre ) );

            $manager->persist( $emprunt );
            echo "Emprunt $i\n";
        }
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [ LivreFixtures::class, AbonneFixtures::class ];
    }
}
