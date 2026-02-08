<?php

namespace App\Entity;

use App\Repository\ActionControleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionControleRepository::class)]
class ActionControle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $action = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: 'json')] // ✅ Spécifier le type
    private array $roles = [];

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $masque = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    // ✅ Supprimer toutes les méthodes isSousAdmin(), isDRJ(), etc.

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function isMasque(): ?bool
    {
        return $this->masque;
    }

    public function setMasque(bool $masque): self
    {
        $this->masque = $masque;
        return $this;
    }

    // ✅ Getter pour le formulaire
    public function getRole(): ?string
    {
        return $this->roles[0] ?? null;
    }
}