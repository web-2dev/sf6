<?php

namespace App\Enum;

enum StatutLecture : string
{
    case AUCUN = "_";
    case A_LIRE = 'a_lire';
    case COMMENCE = 'commence';
    case LU = 'lu';
    case ABANDONNE = 'abandonne';

    function label() {
        switch ($this) {
            case self::AUCUN:
                return "";
                break;
            
            case self::A_LIRE :
                return "à lire";
                break;
            
            case self::LU :
                return "lu";
                break;
            
            case self::COMMENCE :
                return "commencé";
                break;
            
            case self::ABANDONNE :
                return "abandonné";
                break;
            
        }
        return $this->value;
    }
}
