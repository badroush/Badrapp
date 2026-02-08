<?php

namespace App\Entity;

use App\Repository\ItemPanierAchatCollectifRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemPanierAchatCollectifRepository::class)]
class ItemPanierAchatCollectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PanierAchatCollectif $panier = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?DetailAchatsCollectif $detailAchatCollectif = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPanier(): ?PanierAchatCollectif
    {
        return $this->panier;
    }

    public function setPanier(?PanierAchatCollectif $panier): static
    {
        $this->panier = $panier;
        return $this;
    }

    public function getDetailAchatCollectif(): ?DetailAchatsCollectif
    {
        return $this->detailAchatCollectif;
    }

    public function setDetailAchatCollectif(?DetailAchatsCollectif $detailAchatCollectif): static
    {
        $this->detailAchatCollectif = $detailAchatCollectif;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }
}