<?php

namespace App\Entity;

use App\Repository\PanierAchatCollectifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: PanierAchatCollectifRepository::class)]
class PanierAchatCollectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etablissement $etablissement = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChapitreAchatsCollectif $chapitreAchatsCollectif = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: 'integer')]
    private ?int $anneeAchats = null;

    #[ORM\OneToMany(mappedBy: 'panier', targetEntity: ItemPanierAchatCollectif::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    #[ORM\Column(type: 'datetime_immutable')]
private DateTimeImmutable $createdAt;

public function getCreatedAt(): DateTimeImmutable
{
    return $this->createdAt;
}

public function setCreatedAt(DateTimeImmutable $createdAt): self
{
    $this->createdAt = $createdAt;
    return $this;
}
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): static
    {
        $this->etablissement = $etablissement;
        return $this;
    }

    public function getChapitreAchatsCollectif(): ?ChapitreAchatsCollectif
    {
        return $this->chapitreAchatsCollectif;
    }

    public function setChapitreAchatsCollectif(?ChapitreAchatsCollectif $chapitreAchatsCollectif): static
    {
        $this->chapitreAchatsCollectif = $chapitreAchatsCollectif;
        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getAnneeAchats(): ?int
    {
        return $this->anneeAchats;
    }

    public function setAnneeAchats(int $anneeAchats): static
    {
        $this->anneeAchats = $anneeAchats;
        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ItemPanierAchatCollectif $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setPanier($this);
        }

        return $this;
    }

    public function removeItem(ItemPanierAchatCollectif $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getPanier() === $this) {
                $item->setPanier(null);
            }
        }

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getQuantite() * $item->getDetailAchatCollectif()->getPrixVente();
        }
        return $total;
    }
    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'en_cours'])]
    private string $etat = 'en_cours';

    public function getEtat(): string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }
}
