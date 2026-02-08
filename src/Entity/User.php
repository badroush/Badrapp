<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
<<<<<<< HEAD
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
// use DateTimeImmutable;
use DateTimeImmutable;
use PhpParser\Node\Stmt\ElseIf_;
=======
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 180)]
    private ?string $nomp = null;
<<<<<<< HEAD

=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

<<<<<<< HEAD
    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isSousAdmin = false;

    #[ORM\ManyToOne(targetEntity: Etablissement::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Etablissement $etablissement = null;

    #[ORM\Column(length: 10, unique: true)]
    private string $matricule;

    #[ORM\Column(length: 8, unique: true)]
    private string $cin;

    #[ORM\ManyToOne(targetEntity: Grade::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Grade $grade = null;

=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    /**
     * @var Collection<int, Materiel>
     */
    #[ORM\OneToMany(targetEntity: Materiel::class, mappedBy: 'sousAdmin')]
    private Collection $materiels;

<<<<<<< HEAD
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $avatar = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $passcode = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $passcodeExpiresAt = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isPasscodeVerified = false;

    public function getPasscode(): ?string
    {
        return $this->passcode;
    }

    public function setPasscode(?string $passcode): self
    {
        $this->passcode = $passcode;
        return $this;
    }

    public function getPasscodeExpiresAt(): ?DateTimeImmutable
    {
        return $this->passcodeExpiresAt;
    }

    public function setPasscodeExpiresAt(?DateTimeImmutable $passcodeExpiresAt): self
    {
        $this->passcodeExpiresAt = $passcodeExpiresAt;
        return $this;
    }

    public function isPasscodeVerified(): bool
    {
        return $this->isPasscodeVerified;
    }

    public function setPasscodeVerified(bool $isPasscodeVerified): self
    {
        $this->isPasscodeVerified = $isPasscodeVerified;
        return $this;
    }
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;
        return $this;
    }
    #[ORM\OneToMany(mappedBy: 'responsableDemande', targetEntity: DemandeMaintenance::class)]
    private Collection $demandeMaintenances;

    #[ORM\OneToMany(mappedBy: 'technicienAssigne', targetEntity: DemandeMaintenance::class)]
    private Collection $demandesAssignees;
    public function __construct()
    {
        $this->materiels = new ArrayCollection();
        $this->demandeMaintenances = new ArrayCollection();
        $this->demandesAssignees = new ArrayCollection();
    }

    public function getDemandeMaintenances(): Collection
    {
        return $this->demandeMaintenances;
    }

    public function getDemandesAssignees(): Collection
    {
        return $this->demandesAssignees;
    }
=======
    public function __construct()
    {
        $this->materiels = new ArrayCollection();
    }

>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

<<<<<<< HEAD
    public function getNomp(): ?string
    {
        return $this->nomp;
    }

    public function setNomp(string $nomp): static
    {
        $this->nomp = $nomp;

        return $this;
    }
    public function getMatricule(): string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;
        return $this;
    }

    public function getCin(): string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;
        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): static
    {
        $this->grade = $grade;
        return $this;
    }

=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
<<<<<<< HEAD
        if ($this->isSousAdmin() && $this->getEtablissement()) {
            $roles[] = 'ROLE_ETABLISSEMENT_' . $this->getEtablissement()->getId() . '_ADMIN';
        } elseif ($this->isDRJ()) {
            $roles[] = 'ROLE_DRJ';
        } elseif ($this->isDRS()) {
            $roles[] = 'ROLE_DRS';
        } elseif ($this->isMAG()) {
            $roles[] = 'ROLE_MAG';
        } elseif ($this->isFNC()) {
            $roles[] = 'ROLE_FNC';
        }
         elseif ($this->isTech()) {
            $roles[] = 'ROLE_TECH';
        }
        return array_unique($roles);
    }
=======
        // Ajouter un rôle spécifique à l'établissement si sous-admin
        if ($this->isSousAdmin() && $this->getEtablissement()) {
            $roles[] = 'ROLE_ETABLISSEMENT_' . $this->getEtablissement()->getId() . '_ADMIN';
        }
        return array_unique($roles);
        }
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

<<<<<<< HEAD
=======
    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    // ✅ Getter pour isVerified
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

<<<<<<< HEAD
=======
    // ✅ Setter pour isVerified
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

<<<<<<< HEAD
    public function isSousAdmin(): bool
    {
        return $this->isSousAdmin;
    }

    public function setIsSousAdmin(bool $isSousAdmin): self
    {
        $this->isSousAdmin = $isSousAdmin;
        return $this;
    }

    private function isDRJ(): bool
    {
        return in_array('ROLE_DRJ', $this->roles, true);
    }

    private function isDRS(): bool
    {
        return in_array('ROLE_DRS', $this->roles, true);
    }

    private function isMAG(): bool
    {
        return in_array('ROLE_MAG', $this->roles, true);
    }

    private function isFNC(): bool
    {
        return in_array('ROLE_FNC', $this->roles, true);
    }

    private function isTech(): bool
    {
        return in_array('ROLE_TECH', $this->roles, true);
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;
        return $this;
    }

=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
<<<<<<< HEAD
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);
=======
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    /**
     * @return Collection<int, Materiel>
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): static
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels->add($materiel);
            $materiel->setSousAdmin($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): static
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getSousAdmin() === $this) {
                $materiel->setSousAdmin(null);
            }
        }

        return $this;
    }
<<<<<<< HEAD
    // function hasrole
=======
#[ORM\Column(type: 'boolean', options: ['default' => false])]
private bool $isSousAdmin = false;

public function isSousAdmin(): bool
{
    return $this->isSousAdmin;
}

public function setIsSousAdmin(bool $isSousAdmin): self
{
    $this->isSousAdmin = $isSousAdmin;
    return $this;
}
#[ORM\ManyToOne(targetEntity: Etablissement::class)]
#[ORM\JoinColumn(nullable: true)]
private ?Etablissement $etablissement = null;

public function getEtablissement(): ?Etablissement
{
    return $this->etablissement;
}

public function setEtablissement(?Etablissement $etablissement): self
{
    $this->etablissement = $etablissement;
    return $this;
}
public function getNomp(): ?string
    {
        return $this->nomp;
    }
    public function setNomp(string $nomp): static
    {
        $this->nomp = $nomp;

        return $this;
    }

>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

}
