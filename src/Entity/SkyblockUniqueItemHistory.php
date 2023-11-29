<?php

namespace App\Entity;

use App\Repository\SkyblockUniqueItemHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkyblockUniqueItemHistoryRepository::class)]
class SkyblockUniqueItemHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ownerUuid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $observed = null;

    #[ORM\ManyToOne(inversedBy: 'skyblockUniqueItemHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SkyblockUniqueItem $uniqueItem = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnerUuid(): ?string
    {
        return $this->ownerUuid;
    }

    public function setOwnerUuid(string $ownerUuid): static
    {
        $this->ownerUuid = $ownerUuid;

        return $this;
    }

    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    public function setOwnerName(?string $ownerName): static
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    public function getObserved(): ?\DateTimeInterface
    {
        return $this->observed;
    }

    public function setObserved(\DateTimeInterface $observed): static
    {
        $this->observed = $observed;

        return $this;
    }

    public function getUniqueItem(): ?SkyblockUniqueItem
    {
        return $this->uniqueItem;
    }

    public function setUniqueItem(?SkyblockUniqueItem $uniqueItem): static
    {
        $this->uniqueItem = $uniqueItem;

        return $this;
    }

    public function __toString() {
        return $this->getOwnerUuid() . " - " . $this->getObserved()->format('d.m.Y H:i:s') . " at price " . ($this->getValue() ?? "UNKNOWN");
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }
}
