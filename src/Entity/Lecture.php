<?php

namespace App\Entity;

use App\Repository\LectureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LectureRepository::class)]
class Lecture extends ParentEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

        #[ORM\ManyToOne(inversedBy: 'lectures')]
        #[ORM\JoinColumn(nullable: false)]
    private ?Abonne $abonne = null;

        #[ORM\ManyToOne(inversedBy: 'lectures')]
    private ?Livre $livre = null;

        #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $quand = null;

        #[ORM\Column]
    private ?int $nb = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuand(): ?\DateTimeInterface
    {
        return $this->quand;
    }

    public function setQuand(?\DateTimeInterface $quand): static
    {
        $this->quand = $quand;

        return $this;
    }

    public function getNb(): ?int
    {
        return $this->nb;
    }

    public function setNb(int $nb): static
    {
        $this->nb = $nb;

        return $this;
    }
}
