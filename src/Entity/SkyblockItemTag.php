<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SkyblockItemTagRepository;

#[ORM\Entity(repositoryClass: SkyblockItemTagRepository::class)]
class SkyblockItemTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: SkyblockItem::class, mappedBy: 'tags')]
    private Collection $skyblockItems;

    public function __construct()
    {
        $this->skyblockItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    public function __toString() {
        return $this->name;
    }

    /**
     * @return Collection<int, SkyblockItem>
     */
    public function getSkyblockItems(): Collection
    {
        return $this->skyblockItems;
    }

    public function addSkyblockItem(SkyblockItem $skyblockItem): self
    {
        if (!$this->skyblockItems->contains($skyblockItem)) {
            $this->skyblockItems->add($skyblockItem);
            $skyblockItem->addTag($this);
        }

        return $this;
    }

    public function removeSkyblockItem(SkyblockItem $skyblockItem): self
    {
        if ($this->skyblockItems->removeElement($skyblockItem)) {
            $skyblockItem->removeTag($this);
        }

        return $this;
    }
}
