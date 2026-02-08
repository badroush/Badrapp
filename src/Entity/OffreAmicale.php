<?php

namespace App\Entity;

use App\Repository\OffreAmicaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreAmicaleRepository::class)]
class OffreAmicale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 50)]
    private ?string $etat = null; // active, inactive, terminee

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: DemandeAmicale::class)]
    private Collection $demandes;

    #[ORM\Column(type: 'integer', nullable: true)]
private ?int $dureeEnMois = null;

#[ORM\Column(type: 'decimal', precision: 10, scale: 3, nullable: true)]
private ?float $montantParMois = null;

#[ORM\Column(type: 'decimal', precision: 10, scale: 3, nullable: true)]
private ?float $fraisInscription = null;


#[ORM\Column(type: 'integer', nullable: true)]
private ?int $annee = null;

public function getAnnee(): ?int
{
    return $this->annee;
}

public function setAnnee(?int $annee): self
{
    $this->annee = $annee;
    return $this;
}
// Optionnel : champ calculé (non persisté)
public function getTotal(): float
{
    $total = ($this->dureeEnMois ?? 0) * ($this->montantParMois ?? 0);
    if ($this->fraisInscription !== null) {
        $total += $this->fraisInscription;
    }
    return $total;
}

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getDateDebut(): ?\DateTimeInterface { return $this->dateDebut; }
    public function setDateDebut(?\DateTimeInterface $dateDebut): self { $this->dateDebut = $dateDebut; return $this; }
    public function getDateFin(): ?\DateTimeInterface { return $this->dateFin; }
    public function setDateFin(?\DateTimeInterface $dateFin): self { $this->dateFin = $dateFin; return $this; }
    public function getEtat(): ?string { return $this->etat; }
    public function setEtat(string $etat): self { $this->etat = $etat; return $this; }
    public function getDemandes(): Collection { return $this->demandes; }
    public function getDureeEnMois(): ?int
{
    return $this->dureeEnMois;
}

public function setDureeEnMois(?int $dureeEnMois): self
{
    $this->dureeEnMois = $dureeEnMois;
    return $this;
}

public function getMontantParMois(): ?float
{
    return $this->montantParMois;
}

public function setMontantParMois(?float $montantParMois): self
{
    $this->montantParMois = $montantParMois;
    return $this;
}

public function getFraisInscription(): ?float
{
    return $this->fraisInscription;
}

public function setFraisInscription(?float $fraisInscription): self
{
    $this->fraisInscription = $fraisInscription;
    return $this;
}

}