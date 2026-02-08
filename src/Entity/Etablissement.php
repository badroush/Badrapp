<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    /**
     * @var Collection<int, Materiel>
     */
    #[ORM\OneToMany(targetEntity: Materiel::class, mappedBy: 'etablissement')]
    private Collection $sousAdmin;

<<<<<<< HEAD
    /**
     * @var Collection<int, Budget>
     */
    #[ORM\OneToMany(targetEntity: Budget::class, mappedBy: 'idEtablissement')]
    private Collection $budgets;

    /**
     * @var Collection<int, RessourceAffectation>
     */
    #[ORM\OneToMany(targetEntity: RessourceAffectation::class, mappedBy: 'idEtablissement')]
    private Collection $ressourceAffectations;

    public function __construct()
    {
        $this->sousAdmin = new ArrayCollection();
        $this->budgets = new ArrayCollection();
        $this->ressourceAffectations = new ArrayCollection();
        $this->connexionInternets = new ArrayCollection();
        $this->adhesions = new ArrayCollection();
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }
=======
    public function __construct()
    {
        $this->sousAdmin = new ArrayCollection();
    }

>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

<<<<<<< HEAD
    #[ORM\OneToMany(mappedBy: 'etablissement', targetEntity: ConnexionInternet::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $connexionInternets;

    public function getConnexionInternets(): Collection
    {
        return $this->connexionInternets;
    }
    #[ORM\OneToMany(mappedBy: 'etablissement', targetEntity: DemandeMaintenance::class)]
    private Collection $demandeMaintenances;

    /**
     * @var Collection<int, Adhesion>
     */
    #[ORM\OneToMany(targetEntity: Adhesion::class, mappedBy: 'etablissement')]
    private Collection $adhesions;

    public function getDemandeMaintenances(): Collection
    {
        return $this->demandeMaintenances;
    }

    public function addConnexionInternet(ConnexionInternet $connexionInternet): static
    {
        if (!$this->connexionInternets->contains($connexionInternet)) {
            $this->connexionInternets->add($connexionInternet);
            $connexionInternet->setEtablissement($this);
        }

        return $this;
    }

    public function removeConnexionInternet(ConnexionInternet $connexionInternet): static
    {
        if ($this->connexionInternets->removeElement($connexionInternet)) {
            // set the owning side to null (unless already changed)
            if ($connexionInternet->getEtablissement() === $this) {
                $connexionInternet->setEtablissement(null);
            }
        }

        return $this;
    }

=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    /**
     * @return Collection<int, Materiel>
     */
    public function getMateriels(): Collection
    {
        return $this->sousAdmin;
    }

    public function addMateriel(Materiel $materiel): static
    {
        if (!$this->sousAdmin->contains($materiel)) {
            $this->sousAdmin->add($materiel);
            $materiel->setEtablissement($this);
        }

        return $this;
    }

    public function removeSousAdmin(Materiel $sousAdmin): static
    {
        if ($this->sousAdmin->removeElement($sousAdmin)) {
            // set the owning side to null (unless already changed)
            if ($sousAdmin->getEtablissement() === $this) {
                $sousAdmin->setEtablissement(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->nom ?? 'N/A';
    }
<<<<<<< HEAD

    /**
     * @return Collection<int, Budget>
     */
    public function getBudgets(): Collection
    {
        return $this->budgets;
    }

    public function addBudget(Budget $budget): static
    {
        if (!$this->budgets->contains($budget)) {
            $this->budgets->add($budget);
            $budget->setIdEtablissement($this);
        }

        return $this;
    }

    public function removeBudget(Budget $budget): static
    {
        if ($this->budgets->removeElement($budget)) {
            // set the owning side to null (unless already changed)
            if ($budget->getIdEtablissement() === $this) {
                $budget->setIdEtablissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RessourceAffectation>
     */
    public function getRessourceAffectations(): Collection
    {
        return $this->ressourceAffectations;
    }

    public function addRessourceAffectation(RessourceAffectation $ressourceAffectation): static
    {
        if (!$this->ressourceAffectations->contains($ressourceAffectation)) {
            $this->ressourceAffectations->add($ressourceAffectation);
            $ressourceAffectation->setIdEtablissement($this);
        }

        return $this;
    }

    public function removeRessourceAffectation(RessourceAffectation $ressourceAffectation): static
    {
        if ($this->ressourceAffectations->removeElement($ressourceAffectation)) {
            // set the owning side to null (unless already changed)
            if ($ressourceAffectation->getIdEtablissement() === $this) {
                $ressourceAffectation->setIdEtablissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adhesion>
     */
    public function getAdhesions(): Collection
    {
        return $this->adhesions;
    }

    public function addAdhesion(Adhesion $adhesion): static
    {
        if (!$this->adhesions->contains($adhesion)) {
            $this->adhesions->add($adhesion);
            $adhesion->setEtablissement($this);
        }

        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): static
    {
        if ($this->adhesions->removeElement($adhesion)) {
            // set the owning side to null (unless already changed)
            if ($adhesion->getEtablissement() === $this) {
                $adhesion->setEtablissement(null);
            }
        }

        return $this;
    }
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
}
