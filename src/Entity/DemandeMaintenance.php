<?php
// src/Entity/DemandeMaintenance.php

namespace App\Entity;

use App\Repository\DemandeMaintenanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: DemandeMaintenanceRepository::class)]
class DemandeMaintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'demandeMaintenances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etablissement $etablissement = null;

    #[ORM\ManyToOne(inversedBy: 'demandeMaintenances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $responsableDemande = null;

    #[ORM\ManyToOne(inversedBy: 'demandesAssignees')]
    #[ORM\JoinColumn(nullable: true)] // Peut être null avant assignation
    private ?User $technicienAssigne = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 20)]
    private ?string $priorite = null; // 'faible', 'moyenne', 'haute', 'critique'

    #[ORM\Column(length: 20, options: ['default' => 'envoyee'])]
    private ?string $statut = 'envoyee'; // 'envoyee', 'en_cours', 'resolue', 'reportee', 'rejetee'

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTraitement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $messageResolution = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateCreation = null;


    #[ORM\OneToMany(mappedBy: 'demande', targetEntity: ReponseTechnique::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $reponsesTechniques;
    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->reponsesTechniques = new ArrayCollection();
    }

    /**
 * @return Collection<int, ReponseTechnique>
 */
public function getReponsesTechniques(): Collection
{
    return $this->reponsesTechniques;
}

public function addReponsesTechnique(ReponseTechnique $reponsesTechnique): static
{
    if (!$this->reponsesTechniques->contains($reponsesTechnique)) {
        $this->reponsesTechniques->add($reponsesTechnique);
        $reponsesTechnique->setDemande($this);
    }

    return $this;
}

public function removeReponsesTechnique(ReponseTechnique $reponsesTechnique): static
{
    if ($this->reponsesTechniques->removeElement($reponsesTechnique)) {
        // set the owning side to null (unless already changed)
        if ($reponsesTechnique->getDemande() === $this) {
            $reponsesTechnique->setDemande(null);
        }
    }

    return $this;
}

    public function getId(): ?int { return $this->id; }
    public function getEtablissement(): ?Etablissement { return $this->etablissement; }
    public function setEtablissement(?Etablissement $etablissement): static { $this->etablissement = $etablissement; return $this; }
    public function getResponsableDemande(): ?User { return $this->responsableDemande; }
    public function setResponsableDemande(?User $responsableDemande): static { $this->responsableDemande = $responsableDemande; return $this; }
    public function getTechnicienAssigne(): ?User { return $this->technicienAssigne; }
    public function setTechnicienAssigne(?User $technicienAssigne): static { $this->technicienAssigne = $technicienAssigne; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }
    public function getPriorite(): ?string { return $this->priorite; }
    public function setPriorite(string $priorite): static { $this->priorite = $priorite; return $this; }
    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }
    public function getDateTraitement(): ?\DateTimeInterface { return $this->dateTraitement; }
    public function setDateTraitement(?\DateTimeInterface $dateTraitement): static { $this->dateTraitement = $dateTraitement; return $this; }
    public function getDateFin(): ?\DateTimeInterface { return $this->dateFin; }
    public function setDateFin(?\DateTimeInterface $dateFin): static { $this->dateFin = $dateFin; return $this; }
    public function getMessageResolution(): ?string { return $this->messageResolution; }
    public function setMessageResolution(?string $messageResolution): static { $this->messageResolution = $messageResolution; return $this; }
    public function getDateCreation(): ?\DateTimeInterface { return $this->dateCreation; }
}