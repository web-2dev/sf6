<?php

namespace App\Enum;

enum Provenance : string
{
    // case INDEFINI = "";
    case ACHAT = "acheté";
    case EMPRUNT = "emprunté";
    case CADEAU = "offert";
    case RECUP = "trouvé";
    case SUPPR = "jeté";
    case DON = "donné";
    case VENTE = "vendu";
    case RETOUR = "rendu";

    
    public static function sorties(): array
    {
        return [
            self::SUPPR,
            self::DON,
            self::VENTE,
            self::RETOUR,
        ];
    }
}
