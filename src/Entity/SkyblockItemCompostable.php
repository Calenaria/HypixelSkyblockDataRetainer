<?php

namespace App\Entity;

use App\Repository\SkyblockItemCompostableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkyblockItemCompostableRepository::class)]
class SkyblockItemCompostable
{
    public const COMPOST_BASE_COST = 4000;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $value = null;

    #[ORM\OneToOne(inversedBy: 'skyblockItemCompostable', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?SkyblockItem $skyblockItem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getSkyblockItem(): ?SkyblockItem
    {
        return $this->skyblockItem;
    }

    public function setSkyblockItem(SkyblockItem $skyblockItem): self
    {
        $this->skyblockItem = $skyblockItem;

        return $this;
    }

    public function __toString() {
        return $this->skyblockItem->getName();
    }
}
