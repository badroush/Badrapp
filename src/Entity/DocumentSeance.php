<?php

namespace App\Entity;

use App\Repository\DocumentSeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentSeanceRepository::class)]
class DocumentSeance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fichier = null;

    /**
     * @var Collection<int, SeancePlenaire>
     */
    #[ORM\ManyToMany(targetEntity: SeancePlenaire::class, mappedBy: 'documents')]
    private Collection $seancePlenaires;

    public function __construct()
    {
        $this->seancePlenaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): static
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * @return Collection<int, SeancePlenaire>
     */
    public function getSeancePlenaires(): Collection
    {
        return $this->seancePlenaires;
    }

    public function addSeancePlenaire(SeancePlenaire $seancePlenaire): static
    {
        if (!$this->seancePlenaires->contains($seancePlenaire)) {
            $this->seancePlenaires->add($seancePlenaire);
            $seancePlenaire->addDocument($this);
        }

        return $this;
    }

    public function removeSeancePlenaire(SeancePlenaire $seancePlenaire): static
    {
        if ($this->seancePlenaires->removeElement($seancePlenaire)) {
            $seancePlenaire->removeDocument($this);
        }

        return $this;
    }
}
