<?php

namespace App\Entity;

use App\Repository\DelegationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DelegationRepository::class)]
class Delegation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, AssociationSportif>
     */
    #[ORM\OneToMany(targetEntity: AssociationSportif::class, mappedBy: 'delegation')]
    private Collection $associationSportifs;

    public function __construct()
    {
        $this->associationSportifs = new ArrayCollection();
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

    /**
     * @return Collection<int, AssociationSportif>
     */
    public function getAssociationSportifs(): Collection
    {
        return $this->associationSportifs;
    }

    public function addAssociationSportif(AssociationSportif $associationSportif): static
    {
        if (!$this->associationSportifs->contains($associationSportif)) {
            $this->associationSportifs->add($associationSportif);
            $associationSportif->setDelegation($this);
        }

        return $this;
    }

    public function removeAssociationSportif(AssociationSportif $associationSportif): static
    {
        if ($this->associationSportifs->removeElement($associationSportif)) {
            // set the owning side to null (unless already changed)
            if ($associationSportif->getDelegation() === $this) {
                $associationSportif->setDelegation(null);
            }
        }

        return $this;
    }
}
