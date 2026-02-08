<?php

namespace App\Entity;

use App\Repository\AdhesionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdhesionRepository::class)]
class Adhesion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateNaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $etatCivil = null;

    #[ORM\Column]
    private ?int $anneeAdhesion = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateAdhesion = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'adhesions')]
    private ?Etablissement $etablissement = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
private bool $valider = false;

#[ORM\Column(type: 'datetime', nullable: true)]
private ?\DateTimeInterface $imprimer = null;

// Getters & Setters

public function getValider(): bool
{
    return $this->valider;
}

public function setValider(bool $valider): self
{
    $this->valider = $valider;
    return $this;
}

public function getImprimer(): ?\DateTimeInterface
{
    return $this->imprimer;
}

public function setImprimer(?\DateTimeInterface $imprimer): self
{
    $this->imprimer = $imprimer;
    return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTime $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEtatCivil(): ?int
    {
        return $this->etatCivil;
    }

    public function setEtatCivil(int $etatCivil): static
    {
        $this->etatCivil = $etatCivil;

        return $this;
    }

    public function getAnneeAdhesion(): ?int
    {
        return $this->anneeAdhesion;
    }

    public function setAnneeAdhesion(int $anneeAdhesion): static
    {
        $this->anneeAdhesion = $anneeAdhesion;

        return $this;
    }

    public function getDateAdhesion(): ?\DateTime
    {
        return $this->dateAdhesion;
    }

    public function setDateAdhesion(\DateTime $dateAdhesion): static
    {
        $this->dateAdhesion = $dateAdhesion;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
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

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }
    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }
}
