<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class ParentEntity
{
    // TODO • prop ID
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $creation;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $modification;
    
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $suppression;


    
    /**
     * Remplir (hydrater) un objet entité à partir d'un array associatif
     */
    public function fromArray(array $data): static {
        foreach ($data as $property => $value) {
            $setter = "set" . ucfirst($property);
            if(method_exists($this, $setter)) $this->$setter($value);
        }
        return $this;
    }
    
    public function __toString()
    {
        $className = get_called_class();                            // $className = "App\Entity\Livre"
        $className = str_replace("App\Entity\\", "", $className);   // $className = "Livre"
        $className = strtolower($className);                        // $className = "livre"
        return $className;
    }  
    
    public function table() {
        $className = get_called_class();                            // $className = "App\Entity\Livre"
        $className = str_replace("App\Entity\\", "", $className);   // $className = "Livre"
        $className = strtolower($className);                        // $className = "livre"
        return $className;
    }

    /**
     * Get the value of creation
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * Set the value of creation
     */
    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * Get the value of modification
     */
    public function getModification()
    {
        return $this->modification;
    }

    /**
     * Set the value of modification
     */
    public function setModification(\DateTimeInterface $modification): self
    {
        $this->modification = $modification;

        return $this;
    }


    /**
     * Get the value of suppression
     */
    public function getSuppression()
    {
        return $this->suppression;
    }

    /**
     * Set the value of suppression
     */
    public function setSuppression(\DateTimeInterface $suppression): self
    {
        $this->suppression = $suppression;

        return $this;
    }
}
