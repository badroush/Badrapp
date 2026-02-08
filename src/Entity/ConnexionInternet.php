<?php
// src/Entity/ConnexionInternet.php

namespace App\Entity;

use App\Repository\ConnexionInternetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConnexionInternetRepository::class)]
class ConnexionInternet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'connexionInternets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etablissement $etablissement = null;

    #[ORM\Column(length: 50)]
    private ?string $type_connexion = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $reference_modem = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $numero_ligne = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $debit = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_mise_en_marche = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $fournisseur = null;

    #[ORM\Column(length: 20, options: ['default' => 'active'])]
    private ?string $etat_connexion = 'active';

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

    public function getTypeConnexion(): ?string
    {
        return $this->type_connexion;
    }

    public function setTypeConnexion(string $type_connexion): static
    {
        $this->type_connexion = $type_connexion;

        return $this;
    }

    public function getReferenceModem(): ?string
    {
        return $this->reference_modem;
    }

    public function setReferenceModem(?string $reference_modem): static
    {
        $this->reference_modem = $reference_modem;

        return $this;
    }

    public function getNumeroLigne(): ?string
    {
        return $this->numero_ligne;
    }

    public function setNumeroLigne(?string $numero_ligne): static
    {
        $this->numero_ligne = $numero_ligne;

        return $this;
    }

    public function getDebit(): ?string
    {
        return $this->debit;
    }

    public function setDebit(?string $debit): static
    {
        $this->debit = $debit;

        return $this;
    }

    public function getDateMiseEnMarche(): ?\DateTimeInterface
    {
        return $this->date_mise_en_marche;
    }

    public function setDateMiseEnMarche(?\DateTimeInterface $date_mise_en_marche): static
    {
        $this->date_mise_en_marche = $date_mise_en_marche;

        return $this;
    }

    public function getFournisseur(): ?string
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?string $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getEtatConnexion(): ?string
    {
        return $this->etat_connexion;
    }

    public function setEtatConnexion(string $etat_connexion): static
    {
        $this->etat_connexion = $etat_connexion;

        return $this;
    }
}