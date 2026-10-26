<?php

namespace App\Entity;

use App\Repository\BiblioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BiblioRepository::class)]
class Biblio extends ParentEntity
{

        #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom = null;

        #[ORM\OneToOne(inversedBy: 'biblio', cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(nullable: false)]
    private ?Abonne $lecteur = null;

    /**
     * @var Collection<int, Exemplaire>
     */
        #[ORM\OneToMany(targetEntity: Exemplaire::class, mappedBy: 'biblio', orphanRemoval: true)]
    private Collection $exemplaires;

    public function __construct()
    {
        $this->exemplaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLecteur(): ?Abonne
    {
        return $this->lecteur;
    }

    public function setLecteur(Abonne $lecteur): static
    {
        $this->lecteur = $lecteur;

        return $this;
    }

    /**
     * @return Collection<int, Exemplaire>
     */
    public function getExemplaires(): Collection
    {
        return $this->exemplaires;
    }

    public function addExemplaire(Exemplaire $exemplaire): static
    {
        if (!$this->exemplaires->contains($exemplaire)) {
            $this->exemplaires->add($exemplaire);
            $exemplaire->setBiblio($this);
        }

        return $this;
    }

    public function removeExemplaire(Exemplaire $exemplaire): static
    {
        if ($this->exemplaires->removeElement($exemplaire)) {
            // set the owning side to null (unless already changed)
            if ($exemplaire->getBiblio() === $this) {
                $exemplaire->setBiblio(null);
            }
        }

        return $this;
    }
}
