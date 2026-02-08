<?php
// src/Entity/ReponseTechnique.php

namespace App\Entity;

use App\Repository\ReponseTechniqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseTechniqueRepository::class)]
class ReponseTechnique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reponsesTechniques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DemandeMaintenance $demande = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $technicien = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateReponse = null;

    public function __construct()
    {
        $this->dateReponse = new \DateTime();
    }

    // Getters et setters...
    public function getId(): ?int { return $this->id; }
    public function getDemande(): ?DemandeMaintenance { return $this->demande; }
    public function setDemande(?DemandeMaintenance $demande): static { $this->demande = $demande; return $this; }
    public function getTechnicien(): ?User { return $this->technicien; }
    public function setTechnicien(?User $technicien): static { $this->technicien = $technicien; return $this; }
    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): static { $this->contenu = $contenu; return $this; }
    public function getDateReponse(): ?\DateTimeInterface { return $this->dateReponse; }
    public function setDateReponse(\DateTimeInterface $dateReponse): static { $this->dateReponse = $dateReponse; return $this; }
}