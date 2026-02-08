<?php

namespace App\Entity;

use App\Repository\RessourceBudgetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourceBudgetRepository::class)]
class RessourceBudget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, RessourceAffectation>
     */
    #[ORM\OneToMany(targetEntity: RessourceAffectation::class, mappedBy: 'idRessource')]
    private Collection $ressourceAffectations;

    public function __construct()
    {
        $this->ressourceAffectations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
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

    /**
     * @return Collection<int, RessourceAffectation>
     */
    public function getRessourceAffectations(): Collection
    {
        return $this->ressourceAffectations;
    }

    public function addRessourceAffectation(RessourceAffectation $ressourceAffectation): static
    {
        if (!$this->ressourceAffectations->contains($ressourceAffectation)) {
            $this->ressourceAffectations->add($ressourceAffectation);
            $ressourceAffectation->setIdRessource($this);
        }

        return $this;
    }

    public function removeRessourceAffectation(RessourceAffectation $ressourceAffectation): static
    {
        if ($this->ressourceAffectations->removeElement($ressourceAffectation)) {
            // set the owning side to null (unless already changed)
            if ($ressourceAffectation->getIdRessource() === $this) {
                $ressourceAffectation->setIdRessource(null);
            }
        }

        return $this;
    }
}
