<?php

namespace App\Entity;

use App\Repository\SkyblockItemCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkyblockItemCategoryRepository::class)]
class SkyblockItemCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'superCategoryId', targetEntity: SkyblockItemSubcategory::class)]
    private Collection $skyblockItemSubcategories;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: SkyblockItem::class)]
    private Collection $skyblockItems;

    public function __construct()
    {
        $this->skyblockItemSubcategories = new ArrayCollection();
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

    /**
     * @return Collection<int, SkyblockItemSubcategory>
     */
    public function getSkyblockItemSubcategories(): Collection
    {
        return $this->skyblockItemSubcategories;
    }

    public function addSkyblockItemSubcategory(SkyblockItemSubcategory $skyblockItemSubcategory): self
    {
        if (!$this->skyblockItemSubcategories->contains($skyblockItemSubcategory)) {
            $this->skyblockItemSubcategories->add($skyblockItemSubcategory);
            $skyblockItemSubcategory->setSuperCategoryId($this);
        }

        return $this;
    }

    public function removeSkyblockItemSubcategory(SkyblockItemSubcategory $skyblockItemSubcategory): self
    {
        if ($this->skyblockItemSubcategories->removeElement($skyblockItemSubcategory)) {
            // set the owning side to null (unless already changed)
            if ($skyblockItemSubcategory->getSuperCategoryId() === $this) {
                $skyblockItemSubcategory->setSuperCategoryId(null);
            }
        }

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
            $skyblockItem->setCategory($this);
        }

        return $this;
    }

    public function removeSkyblockItem(SkyblockItem $skyblockItem): self
    {
        if ($this->skyblockItems->removeElement($skyblockItem)) {
            // set the owning side to null (unless already changed)
            if ($skyblockItem->getCategory() === $this) {
                $skyblockItem->setCategory(null);
            }
        }

        return $this;
    }
}
