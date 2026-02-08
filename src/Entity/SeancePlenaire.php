<?php

namespace App\Entity;

use App\Repository\SeancePlenaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeancePlenaireRepository::class)]
class SeancePlenaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'seancePlenaires')]
    private ?AssociationSportif $association = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    /**
     * @var Collection<int, DocumentSeance>
     */
    #[ORM\ManyToMany(targetEntity: DocumentSeance::class, inversedBy: 'seancePlenaires')]
    private Collection $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssociation(): ?AssociationSportif
    {
        return $this->association;
    }

    public function setAssociation(?AssociationSportif $association): static
    {
        $this->association = $association;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

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

    /**
     * @return Collection<int, DocumentSeance>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(DocumentSeance $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
        }

        return $this;
    }

    public function removeDocument(DocumentSeance $document): static
    {
        $this->documents->removeElement($document);

        return $this;
    }
}
