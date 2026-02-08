<?php

namespace App\Entity;

use App\Repository\DetailAchatsCollectifRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailAchatsCollectifRepository::class)]
class DetailAchatsCollectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChapitreAchatsCollectif $chapitreAchatsCollectif = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 3)]
    private ?float $prixVente = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;
        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(float $prixVente): static
    {
        $this->prixVente = $prixVente;
        return $this;
    }
}