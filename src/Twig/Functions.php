<?php

namespace App\Twig;

use App\Entity\Abonne;
use Twig\TwigTest;
use Twig\TwigFilter;
use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Twig\Node\Expression\Unary as OpérateurUnaire;
use Twig\Node\Expression\Binary as OpérateurBinaire;
use Twig\ExpressionParser;

class Functions extends AbstractExtension {
    /**
     * 💬 COURS : 
     * • On utilise l'injection de dépendance pour utiliser les classes appelés Services dans Symfony
     * • La classe ParameterBagInterface va permettre de récupérer les valeurs des paramètres du projet (déclarés dans config/services.yaml ) 
     *   
     */
    private $livreRepo;
    private $parametres;

    public function __construct(LivreRepository $livreRepo, ParameterBagInterface $parameterBag) {
        $this->livreRepo = $livreRepo;
        $this->parametres = $parameterBag;
    }

    function livreDisponible(Livre $l): bool {
        return in_array($l, $this->livreRepo->livresDisponibles());
    }

    /**
     * Pour ajouter un filtre ou une fonction accessible aux fichiers TWIG, on ajoute une méthode à cette classe
     * qui hérite de AbstractExtension
     */
    public function autorisations(Abonne $abonne): string {
        $autorisations = "";
        foreach ($abonne->getRoles() as $role ) {
            $autorisations .= $autorisations ? ", " : "";
            switch ($role) {
                case 'ROLE_ADMIN':
                    $autorisations .= "Directeur";
                    break;
                
                case 'ROLE_BIBLIO':
                    $autorisations .= "Bibliothécaire";
                    break;
                
                case 'ROLE_LECTEUR':
                    $autorisations .= "Lecteur";
                    break;
                
                case 'ROLE_USER':
                    $autorisations .= "Abonné";
                    break;

                case 'ROLE_DEV':
                    $autorisations .= "Développeur";
                    break;
                
                default:
                    $autorisations .= "Autre";
                    break;
            }
        }
        return $autorisations;
    }

    public function résumé(?string $texte, int $longueur): string
    {
        return strlen($texte) > $longueur ? substr($texte, 0, $longueur) . "[...]" : $texte;
    }

    /**
     * Cette méthode va renvoyer une balise image.
     * @param $nomImage string nom du fichier image. L'image sera recherché à partir du 'chemin_image' défini 
     */
    public function baliseImg($nomImage, $dossier = "", $classes = "", $alt = "") : string
    {
        $balise = "";
        if($dossier && substr($dossier, -1) != "/"){
            $dossier .= "/";    // 🎶 ajoute un "/" en fin de $dossier s'il n'y en a pas déjà
        }
        if( file_exists($this->parametres->get("dossier_images") . $dossier . $nomImage) ) {
            $src =  $this->parametres->get("chemin_images") . $dossier .  $nomImage;
        } else {
            $src = "";
        }
        $alt = $alt ?: $nomImage;
        $balise = "<img src='$src' class='$classes' alt='$alt'>";
        // $balise = html_entity_decode($balise); // COURS obligatoire pour que twig accepte les balises HTML. ⚠ il faut utiliser 'raw'
        return $balise;
    }

    /**
        Besoin d'un 'exit' après un var_dump
     */
    public function exit()
    {
        exit( call_user_func_array("dd", func_get_args()) );
    }

    /**
     * La fonction n'apporte rien de plus par rapport à la fonction is_numeric de PHP mais
     * le but est de la rendre accessible à Twig
     */
    public function estNumerique($variable)
    {
        return is_numeric($variable);
    }

    /**
     * 💬 COURS 
     * Les filtres que l'on veut ajouter doivent être renvoyés dans un array par la fonction getFilters
     * Chaque valeur de cet array est un objet de la classe TwigFilter
     * Les arguments du constructeur de TwigFilter :
     * •     1er : le nom du filtre à utiliser dans les fichiers Twig
     * •     2eme : la fonction (callable) qui est déclaré dans cette classe 
     *                  [ $this, nom_de_la_fonction_dans_la_classe ]

     * Je référence le nouveau filtre grâce à la méthode getFilters()
     * Si je veux ajouter une fonction, j'utilise la méthode getFunctions() et
     * pour ajouter un test, getTests()
     */
    public function getFilters()
    {
        return [ 
            new TwigFilter("autorisations", [$this, "autorisations"]),
            new TwigFilter("img", [$this, "baliseImg"]),
            new TwigFilter("extrait", [$this, "résumé"]),
            new TwigFilter("dispo", [$this, "livreDisponible"]),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('balise_image', [$this, 'baliseImg']),
            new TwigFunction('exit', [$this, 'exit']),
        ];
    }

    public function getTests()
    {
        return [
            new TwigTest("number", [$this, "estNumerique"]),
            new TwigTest("dispo", [$this, "livreDisponible"])
        ];
    }

    /**
     * 💬 Ajouter des variables globales Twig   
     */
    public function getGlobals(){
        return [ 
            "globale"           => "c'est une variable globale",
            "titrePrincipal"    => "Bienvenue à l'e-bliothèque" 
        ];
    }

    public function getOperators()
    {
        return [
            [
                '!' => ['precedence' => 50, 'class' => OpérateurUnaire\NotUnary::class],
            ],
            [
                '||' => ['precedence' => 10, 'class' => OpérateurBinaire\OrBinary::class, 'associativity' => ExpressionParser::OPERATOR_LEFT],
                '&&' => ['precedence' => 15, 'class' => OpérateurBinaire\AndBinary::class, 'associativity' => ExpressionParser::OPERATOR_LEFT],
            ],
        ];
    }    

}
