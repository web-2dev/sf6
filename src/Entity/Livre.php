<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre extends ParentEntity
{
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
    private ?int $id = null;

        #[ORM\Column(length: 50)]
    private ?string $titre = null;

        #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $synopsis = null;

        #[ORM\Column(length: 100, nullable: true)]
    private ?string $couverture = null;

        #[ORM\Column(length: 255)]
    private ?string $url = null;

        #[ORM\ManyToOne(inversedBy: 'livres', cascade:["persist"])]
        #[ORM\JoinColumn(nullable: false)]
        #[Assert\NotNull(message: "Un livre est écrit par un auteur")]
    private ?Auteur $auteur = null;

    /**
     * @var Collection<int, Genre>
     */
        #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'livres')]
    private Collection $genres;

    /**
     * @var Collection<int, Emprunt>
     */
        #[ORM\OneToMany(targetEntity: Emprunt::class, mappedBy: 'livre', orphanRemoval: true)]
    private Collection $emprunts;

    /**
     * @var Collection<int, Critique>
     */
        #[ORM\OneToMany(targetEntity: Critique::class, mappedBy: 'livre', orphanRemoval: true)]
    private Collection $critiques;

    /**
     * @var Collection<int, Lecture>
     */
        #[ORM\OneToMany(targetEntity: Lecture::class, mappedBy: 'livre')]
    private Collection $lectures;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
        $this->critiques = new ArrayCollection();
        $this->lectures = new ArrayCollection();
    }

    // ╔═══════════════════════════════════════════════════════════════════════╗
    // ║                                 AJOUTS                                ║
    // ╚═══════════════════════════════════════════════════════════════════════╝

    public function getTitreAuteur()
    {
        return ucfirst( $this->getTitre() ) . " - " . ucfirst( $this->getAuteur()->getIdentite() );
    }
    /**
     * Retourne la liste des genres liés au livre sous forme de string
     */
    public function getGenre(): string
    {
        $resultat = "";
        foreach($this->genres as $genre){
            if( $resultat != ""){
                $resultat .= ", ";  //💬 si $resultat n'est pas une string vide, je concatène une virgule à $resultat
            }
            $resultat .= $genre->getLibelle();
        }
        return $resultat;
    }   
    
    public function __toString()
    {
        return $this->getTitreAuteur();
    }
    
    // ╔═══════════════════════════════════════════════════════════════════════╗
    // ║                           GETTERS && SETTERS                          ║
    // ╚═══════════════════════════════════════════════════════════════════════╝
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getCouverture(): ?string
    {
        return $this->couverture;
    }

    public function setCouverture(?string $couverture): static
    {
        $this->couverture = $couverture;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setLivre($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getLivre() === $this) {
                $emprunt->setLivre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Critique>
     */
    public function getCritiques(): Collection
    {
        return $this->critiques;
    }

    public function addCritique(Critique $critique): static
    {
        if (!$this->critiques->contains($critique)) {
            $this->critiques->add($critique);
            $critique->setLivre($this);
        }

        return $this;
    }

    public function removeCritique(Critique $critique): static
    {
        if ($this->critiques->removeElement($critique)) {
            // set the owning side to null (unless already changed)
            if ($critique->getLivre() === $this) {
                $critique->setLivre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lecture>
     */
    public function getLectures(): Collection
    {
        return $this->lectures;
    }

    public function addLecture(Lecture $lecture): static
    {
        if (!$this->lectures->contains($lecture)) {
            $this->lectures->add($lecture);
            $lecture->setLivre($this);
        }

        return $this;
    }

    public function removeLecture(Lecture $lecture): static
    {
        if ($this->lectures->removeElement($lecture)) {
            // set the owning side to null (unless already changed)
            if ($lecture->getLivre() === $this) {
                $lecture->setLivre(null);
            }
        }

        return $this;
    }

}
