<?php

namespace App\Entity;

use App\Repository\AssociationSportifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationSportifRepository::class)]
class AssociationSportif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'associationSportifs')]
    private ?Delegation $delegation = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private array $specialites = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateConstruction = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $fax = null;

    #[ORM\Column(length: 255)]
    private ?string $directeur = null;

    #[ORM\Column(length: 20)]
    private ?string $telDirecteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $directeurAdjoint = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telDirAdj = null;

    #[ORM\Column(length: 255)]
    private ?string $secretariatGeneral = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telSecGen = null;

    #[ORM\Column(length: 255)]
    private ?string $tresorier = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telTres = null;

#[ORM\Column(type: Types::INTEGER, nullable: true)]
private ?int $nombreMales = null;

#[ORM\Column(type: Types::INTEGER, nullable: true)]
private ?int $nombreFemelles = null;

#[ORM\ManyToOne(targetEntity: ClasseSportif::class)]
#[ORM\JoinColumn(nullable: false)]
private ClasseSportif $classeSportif;
    /**
     * @var Collection<int, SeancePlenaire>
     */
    #[ORM\OneToMany(targetEntity: SeancePlenaire::class, mappedBy: 'association')]
    private Collection $seancePlenaires;

    public function __construct()
    {
        $this->seancePlenaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDelegation(): ?Delegation
    {
        return $this->delegation;
    }

    public function setDelegation(?Delegation $delegation): static
    {
        $this->delegation = $delegation;

        return $this;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getSpecialites(): array
    {
        return $this->specialites;
    }

    public function setSpecialites(array $specialites): static
    {
        $this->specialites = $specialites;

        return $this;
    }

    public function getDateConstruction(): ?\DateTime
    {
        return $this->dateConstruction;
    }

    public function setDateConstruction(\DateTime $dateConstruction): static
    {
        $this->dateConstruction = $dateConstruction;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): static
    {
        $this->fax = $fax;

        return $this;
    }

    public function getDirecteur(): ?string
    {
        return $this->directeur;
    }

    public function setDirecteur(string $directeur): static
    {
        $this->directeur = $directeur;

        return $this;
    }

    public function getTelDirecteur(): ?string
    {
        return $this->telDirecteur;
    }

    public function setTelDirecteur(string $telDirecteur): static
    {
        $this->telDirecteur = $telDirecteur;

        return $this;
    }

    public function getDirecteurAdjoint(): ?string
    {
        return $this->directeurAdjoint;
    }

    public function setDirecteurAdjoint(?string $directeurAdjoint): static
    {
        $this->directeurAdjoint = $directeurAdjoint;

        return $this;
    }

    public function getTelDirAdj(): ?string
    {
        return $this->telDirAdj;
    }

    public function setTelDirAdj(?string $telDirAdj): static
    {
        $this->telDirAdj = $telDirAdj;

        return $this;
    }

    public function getSecretariatGeneral(): ?string
    {
        return $this->secretariatGeneral;
    }

    public function setSecretariatGeneral(string $secretariatGeneral): static
    {
        $this->secretariatGeneral = $secretariatGeneral;

        return $this;
    }

    public function getTelSecGen(): ?string
    {
        return $this->telSecGen;
    }

    public function setTelSecGen(?string $telSecGen): static
    {
        $this->telSecGen = $telSecGen;

        return $this;
    }

    public function getTresorier(): ?string
    {
        return $this->tresorier;
    }

    public function setTresorier(string $tresorier): static
    {
        $this->tresorier = $tresorier;

        return $this;
    }

    public function getTelTres(): ?string
    {
        return $this->telTres;
    }

    public function setTelTres(?string $telTres): static
    {
        $this->telTres = $telTres;

        return $this;
    }
    public function getNombreMales(): ?int
{
    return $this->nombreMales;
}

public function setNombreMales(?int $nombreMales): self
{
    $this->nombreMales = $nombreMales;

    return $this;
}

public function getNombreFemelles(): ?int
{
    return $this->nombreFemelles;
}

public function setNombreFemelles(?int $nombreFemelles): self
{
    $this->nombreFemelles = $nombreFemelles;

    return $this;
}

public function getClasseSportif(): ?ClasseSportif
{
    return $this->classeSportif;
}

public function setClasseSportif(?ClasseSportif $classeSportif): self
{
    $this->classeSportif = $classeSportif;

    return $this;
}

    /**
     * @return Collection<int, SeancePlenaire>
     */
    public function getSeancePlenaires(): Collection
    {
        return $this->seancePlenaires;
    }

    public function addSeancePlenaire(SeancePlenaire $seancePlenaire): static
    {
        if (!$this->seancePlenaires->contains($seancePlenaire)) {
            $this->seancePlenaires->add($seancePlenaire);
            $seancePlenaire->setAssociation($this);
        }

        return $this;
    }

    public function removeSeancePlenaire(SeancePlenaire $seancePlenaire): static
    {
        if ($this->seancePlenaires->removeElement($seancePlenaire)) {
            // set the owning side to null (unless already changed)
            if ($seancePlenaire->getAssociation() === $this) {
                $seancePlenaire->setAssociation(null);
            }
        }

        return $this;
    }
}
