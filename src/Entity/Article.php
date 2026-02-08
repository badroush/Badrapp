<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks] // ← Ajoute ceci
#[UniqueEntity(fields: ['reference'], message: 'المرجع مستخدم بالفعل.')]
class Article
{
    public function __construct()
{
    $this->prixachat = 0.0;
    $this->prixvente = 0.0;
    $this->seuilalerte = 5;
    $this->stock = 0; // optionnel, mais cohérent
}

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\NotBlank]
    private ?string $reference = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    #[ORM\Column(nullable: true)]
    private ?float $prixachat = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixvente = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column]
    private ?int $seuilalerte = null;



    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $datecreation = null;

    #[ORM\ManyToOne(inversedBy: 'fournisseur')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Fournisseur $fournisseur = null;

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
        $this->nom = strtoupper(trim($nom));
        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }



    public function getPrixachat(): ?float
    {
        return $this->prixachat;
    }

    public function setPrixachat(?float $prixachat): static
    {
        $this->prixachat = $prixachat;

        return $this;
    }

    public function getPrixvente(): ?float
    {
        return $this->prixvente;
    }

    public function setPrixvente(?float $prixvente): static
    {
        $this->prixvente = $prixvente;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSeuilalerte(): ?int
    {
        return $this->seuilalerte;
    }

    public function setSeuilalerte(int $seuilalerte): static
    {
        $this->seuilalerte = $seuilalerte;

        return $this;
    }

    public function getDatecreation(): ?\DateTime
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTime $datecreation): static
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }
    #[ORM\PrePersist]
    public function setDateCreationValue(): void
    {
        if (!$this->datecreation) {
            $this->datecreation = new \DateTime();
        }
    }

    public function generateReference(): string
    {
        // Option 1 : ULID (recommandé)
        return 'CRJS-' . strtoupper(substr((string) new Ulid(), 0, 10));

        // Option 2 : aléatoire personnalisé (moins robuste)
        // return 'REF-' . strtoupper(substr(bin2hex(random_bytes(6)), 0, 8));
    }
}
