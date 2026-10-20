<?php

namespace App\Entity;

use App\Enum\Provenance;
use App\Enum\StatutLecture;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExemplaireRepository;

#[ORM\Entity(repositoryClass: ExemplaireRepository::class)]
class Exemplaire extends ParentEntity
{
     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column]
    private ?int $id = null;

     #[ORM\ManyToOne(inversedBy: 'exemplaires')]
     #[ORM\JoinColumn(nullable: false)]
    private ?Biblio $biblio = null;

     #[ORM\ManyToOne(inversedBy: 'exemplaires')]
     #[ORM\JoinColumn(nullable: false)]
    private ?Livre $livre = null;

     #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $note = null;

     #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

     #[ORM\Column(nullable: true, enumType: StatutLecture::class)]
    private ?StatutLecture $statut = null;
    
     #[ORM\Column(enumType: Provenance::class, nullable: true)]
    private ?Provenance $acquisition = null;

     #[ORM\Column(length: 255, nullable: true)]
    private ?string $sousTitre = null;
     #[ORM\Column(nullable: true)]
    private ?int $tome = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBiblio(): ?Biblio
    {
        return $this->biblio;
    }

    public function setBiblio(?Biblio $biblio): static
    {
        $this->biblio = $biblio;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): static
    {
        $this->livre = $livre;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getStatut(): ?StatutLecture
    {
        return $this->statut;
    }

    public function setStatut(?StatutLecture $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAcquisition(): ?Provenance
    {
        return $this->acquisition;
    }

    public function setAcquisition(?Provenance $acquisition): static
    {
        $this->acquisition = $acquisition;

        return $this;
    }

    public function getSousTitre(): ?string
    {
        return $this->sousTitre;
    }

    public function setSousTitre(?string $sousTitre): static
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }

    public function getTome(): ?int
    {
        return $this->tome;
    }

    public function setTome(?int $tome): static
    {
        $this->tome = $tome;

        return $this;
    }
}
