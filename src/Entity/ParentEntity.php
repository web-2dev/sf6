<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass] // pour marquer l'héritage pour Doctrine
abstract class ParentEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $creation;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $modification;
    
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $suppression;


    
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


// --------------------------------------------------------------
// GETTERS - SETTERS
//---------------------------------------------------------------
    public function getId(): ?int { return $this->id; }

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


// --------------------------------------------------------------
// DOCTRINE
//---------------------------------------------------------------

    // ⚠ Annotations pour gérer les dates des entités      
    // ⚠ 
    #[ORM\PrePersist]
    public function initialiserCreation(): void
    {
        $this->creation = new \DateTimeImmutable();
    }


    #[ORM\PreUpdate]
    public function actualiserModification(): void
    {
        $this->modification = new \DateTimeImmutable();
    }        
}
