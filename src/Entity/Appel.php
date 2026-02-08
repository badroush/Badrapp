<?php

namespace App\Entity;

use App\Repository\AppelRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppelRepository::class)]
class Appel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'appelsEmis')]
#[ORM\JoinColumn(nullable: false)]
private Contact $contactEmetteur;

#[ORM\ManyToOne(inversedBy: 'appelsRecus')]
#[ORM\JoinColumn(nullable: false)]
private Contact $contactRecepteur;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateAppel = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heureAppel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContactEmetteur(): ?Contact
    {
        return $this->contactEmetteur;
    }

    public function setContactEmetteur(?Contact $contactEmetteur): static
    {
        $this->contactEmetteur = $contactEmetteur;

        return $this;
    }

    public function getContactRecepteur(): ?Contact
    {
        return $this->contactRecepteur;
    }

    public function setContactRecepteur(?Contact $contactRecepteur): static
    {
        $this->contactRecepteur = $contactRecepteur;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDateAppel(): ?\DateTime
    {
        return $this->dateAppel;
    }

    public function setDateAppel(\DateTime $dateAppel): static
    {
        $this->dateAppel = $dateAppel;

        return $this;
    }

    public function getHeureAppel(): ?\DateTime
    {
        return $this->heureAppel;
    }

    public function setHeureAppel(\DateTime $heureAppel): static
    {
        $this->heureAppel = $heureAppel;

        return $this;
    }
}
