<?php

namespace App\Entity;

use App\Repository\ChapitreAchatsCollectifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapitreAchatsCollectifRepository::class)]
class ChapitreAchatsCollectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'chapitreAchatsCollectif', targetEntity: DetailAchatsCollectif::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $details;
    
    #[ORM\ManyToOne(targetEntity: ChapitreBudget::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChapitreBudget $chapitreBudget = null;

    public function getChapitreBudget(): ?ChapitreBudget
    {
        return $this->chapitreBudget;
    }

    public function setChapitreBudget(?ChapitreBudget $chapitreBudget): static
    {
        $this->chapitreBudget = $chapitreBudget;
        return $this;
    }

    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): static
    {
        $this->id = $id;
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

    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(DetailAchatsCollectif $detail): static
    {
        if (!$this->details->contains($detail)) {
            $this->details->add($detail);
            $detail->setChapitreAchatsCollectif($this);
        }

        return $this;
    }

    public function removeDetail(DetailAchatsCollectif $detail): static
    {
        if ($this->details->removeElement($detail)) {
            // set the owning side to null (unless already changed)
            if ($detail->getChapitreAchatsCollectif() === $this) {
                $detail->setChapitreAchatsCollectif(null);
            }
        }

        return $this;
    }
}
