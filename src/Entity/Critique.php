<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CritiqueRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CritiqueRepository::class)]
#[ORM\UniqueConstraint(name: "unique_abonne_livre_critique", columns: ["abonne_id", "livre_id"])]
#[UniqueEntity(fields: ["abonne", "livre"], message: "Un abonné ne peut critiquer un film qu'UNE seule fois")]

class Critique extends ParentEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'critiques')]
    #[ORM\JoinColumn(name: "abonne_id", nullable: false)]
    private ?Abonne $abonne = null;

    #[ORM\ManyToOne(inversedBy: 'critiques')]
    #[ORM\JoinColumn(name: "livre_id", nullable: false)]
    private ?Livre $livre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
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

    public function getAbonne(): ?Abonne
    {
        return $this->abonne;
    }

    public function setAbonne(?Abonne $abonne): static
    {
        $this->abonne = $abonne;

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
}
