<?php

namespace App\Trait;

trait Personne
{
    public function getIdentite()
    {
        return trim("$this->prenom $this->nom");
    }

    public function getNomComplet()
    {
        return trim(ucfirst(strtolower($this->prenom)) . " " . strtoupper($this->nom));
    }

}
