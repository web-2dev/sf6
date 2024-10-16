<?php

namespace App\Trait;

trait Personne
{
    public function getIdentite()
    {
        return trim("$this->prenom $this->nom");
    }

}
