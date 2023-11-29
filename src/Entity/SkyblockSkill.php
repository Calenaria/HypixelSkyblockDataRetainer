<?php

namespace App\Entity;

use App\Repository\SkyblockSkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkyblockSkillRepository::class)]
class SkyblockSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'skill', targetEntity: SkyblockItem::class)]
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
            $skyblockItem->setSkill($this);
        }

        return $this;
    }

    public function removeSkyblockItem(SkyblockItem $skyblockItem): self
    {
        if ($this->skyblockItems->removeElement($skyblockItem)) {
            // set the owning side to null (unless already changed)
            if ($skyblockItem->getSkill() === $this) {
                $skyblockItem->setSkill(null);
            }
        }

        return $this;
    }
}
