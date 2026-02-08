<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

<<<<<<< HEAD
    // #[ORM\Column(length: 255)]
    // private ?string $nom = null;

    // #[ORM\Column(type: 'string', length: 100, unique: true)]
    // private ?string $reference = null;
=======
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private ?string $reference = null;
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateAcquisition = null;

    #[ORM\Column(length: 50)]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'sousAdmin')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etablissement $etablissement = null;

    #[ORM\ManyToOne(inversedBy: 'materiels')]
    private ?User $sousAdmin = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?float $nbr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emplacement = null;

<<<<<<< HEAD
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $serie = null;

    #[ORM\ManyToOne(targetEntity: LibelleMateriel::class, inversedBy: 'matériels')]
#[ORM\JoinColumn(nullable: true)] // ✅ Rendre nullable temporairement
private ?LibelleMateriel $libelleMateriel = null;

    // ... tes autres méthodes ...

    public function getSerie(): ?string
    {
        return $this->serie;
    }

    public function setSerie(?string $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    // ✅ Générer la référence automatiquement
    public function __construct()
    {
        // if (!$this->reference) {
        //     $this->generateReference();
        // }
        // if (!$this->dateAcquisition) {
        //     $this->dateAcquisition = new \DateTime(); // Date d'aujourd'hui
        // }
    }

    // public function generateReference(): void
    // {
    //     $date = new \DateTime();
    //     $dateStr = $date->format('Ymd');
    //     $randomNum = str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);
    //     $this->reference = 'MAT-' . $dateStr . '-' . $randomNum;
    // }
=======
    // ✅ Générer la référence automatiquement
    public function __construct()
    {
        if (!$this->reference) {
            $this->generateReference();
        }
        if (!$this->dateAcquisition) {
            $this->dateAcquisition = new \DateTime(); // Date d'aujourd'hui
        }
    }

    public function generateReference(): void
    {
        $date = new \DateTime();
        $dateStr = $date->format('Ymd');
        $randomNum = str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);
        $this->reference = 'MAT-' . $dateStr . '-' . $randomNum;
    }
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    public function getId(): ?int
    {
        return $this->id;
    }

<<<<<<< HEAD
    // public function getNom(): ?string
    // {
    //     return $this->nom;
    // }

    // public function setNom(string $nom): static
    // {
    //     $this->nom = $nom;

    //     return $this;
    // }

    // public function getReference(): ?string
    // {
    //     return $this->reference;
    // }

    // public function setReference(string $reference): static
    // {
    //     $this->reference = $reference;

    //     return $this;
    // }
=======
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

    public function getDateAcquisition(): ?\DateTime
    {
        return $this->dateAcquisition;
    }

    public function setDateAcquisition(\DateTime $dateAcquisition): static
    {
        $this->dateAcquisition = $dateAcquisition;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

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
    public function getSousAdmin(): ?User
    {
        return $this->sousAdmin;
    }

    public function setSousAdmin(?User $sousAdmin): static
    {
        $this->sousAdmin = $sousAdmin;
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }
    public function getNbr(): ?float
    {
        return $this->nbr;
    }
    public function setNbr(?float $nbr): static
    {
        $this->nbr = $nbr;
        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(?string $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }
<<<<<<< HEAD

    public function getLibelleMateriel(): ?libelleMateriel
    {
        return $this->libelleMateriel;
    }

    public function setLibelleMateriel(?libelleMateriel $libelleMateriel): static
    {
        $this->libelleMateriel = $libelleMateriel;

        return $this;
    }
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
}
