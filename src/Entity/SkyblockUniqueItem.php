<?php

namespace App\Entity;

use App\Repository\SkyblockUniqueItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkyblockUniqueItemRepository::class)]
class SkyblockUniqueItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $uuid = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $firstSeen = null;

    #[ORM\OneToMany(mappedBy: 'uniqueItem', targetEntity: SkyblockUniqueItemHistory::class, cascade: ['remove'])]
    private Collection $skyblockUniqueItemHistories;

    #[ORM\ManyToOne]
    private ?SkyblockItem $item = null;

    #[ORM\Column(length: 511)]
    private ?string $plainItemName = null;

    public function __construct()
    {
        $this->skyblockUniqueItemHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getFirstSeen(): ?\DateTimeInterface
    {
        return $this->firstSeen;
    }

    public function setFirstSeen(\DateTimeInterface $firstSeen): static
    {
        $this->firstSeen = $firstSeen;

        return $this;
    }

    /**
     * @return Collection<int, SkyblockUniqueItemHistory>
     */
    public function getSkyblockUniqueItemHistories(): Collection
    {
        return $this->skyblockUniqueItemHistories;
    }

    public function addSkyblockUniqueItemHistory(SkyblockUniqueItemHistory $skyblockUniqueItemHistory): static
    {
        if (!$this->skyblockUniqueItemHistories->contains($skyblockUniqueItemHistory)) {
            $this->skyblockUniqueItemHistories->add($skyblockUniqueItemHistory);
            $skyblockUniqueItemHistory->setUniqueItem($this);
        }

        return $this;
    }

    public function removeSkyblockUniqueItemHistory(SkyblockUniqueItemHistory $skyblockUniqueItemHistory): static
    {
        if ($this->skyblockUniqueItemHistories->removeElement($skyblockUniqueItemHistory)) {
            // set the owning side to null (unless already changed)
            if ($skyblockUniqueItemHistory->getUniqueItem() === $this) {
                $skyblockUniqueItemHistory->setUniqueItem(null);
            }
        }

        return $this;
    }

    public function getHistoryEntries(): int {
        return count($this->getSkyblockUniqueItemHistories());
    }

    public function getAverageValue(): int {
        $total = 0;
        $count = $this->getHistoryEntries() ?? 1;

        foreach ($this->skyblockUniqueItemHistories ?? [] as $history) {
            $total += $history->getValue();
        }

        return $total/$count;
    }

    public function __toString() {
        return $this->getUuid() . " : " . $this->getFirstSeen()->format('d.m.Y H:i:s');
    }

    public function getItem(): ?SkyblockItem
    {
        return $this->item;
    }

    public function setItem(?SkyblockItem $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getPlainItemName(): ?string
    {
        return $this->plainItemName;
    }

    public function setPlainItemName(string $plainItemName): static
    {
        $this->plainItemName = $plainItemName;

        return $this;
    }

    public function getCurrentOwner(): string {
        return $this->getSkyblockUniqueItemHistories()->last()->getOwnerUuid() ?? "";
    }
}
