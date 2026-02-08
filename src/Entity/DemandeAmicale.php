<?php

namespace App\Entity;

use App\Repository\DemandeAmicaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeAmicaleRepository::class)]
class DemandeAmicale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OffreAmicale $offre = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $beneficiaire = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null; // en_attente, valide, refuse, paye

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDemande = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;
    public function __construct()
    {
        $this->dateDemande = new \DateTime();
        $this->statut = 'en_attente'; 
    }

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getOffre(): ?OffreAmicale { return $this->offre; }
    public function setOffre(?OffreAmicale $offre): self { $this->offre = $offre; return $this; }
    public function getBeneficiaire(): ?User { return $this->beneficiaire; }
    public function setBeneficiaire(?User $beneficiaire): self { $this->beneficiaire = $beneficiaire; return $this; }
    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }
    public function getDateDemande(): ?\DateTimeInterface { return $this->dateDemande; }
    public function setDateDemande(\DateTimeInterface $dateDemande): self { $this->dateDemande = $dateDemande; return $this; }
    public function getCommentaire(): ?string { return $this->commentaire; }
    public function setCommentaire(?string $commentaire): self { $this->commentaire = $commentaire; return $this; }
}