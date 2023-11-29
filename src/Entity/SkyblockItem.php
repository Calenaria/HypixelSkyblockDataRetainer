<?php

namespace App\Entity;

use App\Repository\SkyblockItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkyblockItemRepository::class)]
class SkyblockItem
{
    const RARITY_CHOICES = [
        "COMMON" => "COMMON",
        "UNCOMMON" => "UNCOMMON",
        "RARE" => "RARE",
        "EPIC" => "EPIC",
        "LEGENDARY" => "LEGENDARY",
        "MYTHIC" => "MYTHIC",
        "DIVINE" => "DIVINE",
        "SPECIAL" => "SPECIAL",
        "VERY_SPECIAL" => "VERY_SPECIAL",
        "UNOBTAINABLE" => "UNOBTAINABLE"
    ];


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $productId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $material = null;

    #[ORM\Column(length: 255)]
    private ?string $rarity = null;

    #[ORM\Column(length: 255)]
    private ?float $value = null;

    #[ORM\ManyToMany(targetEntity: SkyblockItemTag::class, inversedBy: 'skyblockItems')]
    private Collection $tags;

    #[ORM\ManyToOne(inversedBy: 'skyblockItems')]
    private ?SkyblockItemCategory $category = null;

    #[ORM\OneToOne(mappedBy: 'skyblockItem', cascade: ['persist', 'remove'])]
    private ?SkyblockItemCompostable $skyblockItemCompostable = null;

    #[ORM\ManyToOne(inversedBy: 'skyblockItems')]
    private ?SkyblockSkill $skill = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lore = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): self
    {
        $this->productId = $productId;

        return $this;
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

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(string $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getRarity(): ?string
    {
        return $this->rarity;
    }

    public function setRarity(string $rarity): self
    {
        $this->rarity = $rarity;

        return $this;
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

    /**
     * @return Collection<int, SkyblockItemTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(SkyblockItemTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(SkyblockItemTag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getCategory(): ?SkyblockItemCategory
    {
        return $this->category;
    }

    public function setCategory(?SkyblockItemCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function __toString() {
        return $this->name . " ({$this->getProductId()})";
    }

    public function getSkyblockItemCompostable(): ?SkyblockItemCompostable
    {
        return $this->skyblockItemCompostable;
    }

    public function setSkyblockItemCompostable(SkyblockItemCompostable $skyblockItemCompostable): self
    {
        // set the owning side of the relation if necessary
        if ($skyblockItemCompostable->getSkyblockItem() !== $this) {
            $skyblockItemCompostable->setSkyblockItem($this);
        }

        $this->skyblockItemCompostable = $skyblockItemCompostable;

        return $this;
    }

    public function getSkill(): ?SkyblockSkill
    {
        return $this->skill;
    }

    public function setSkill(?SkyblockSkill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getLore(): ?string
    {
        return $this->lore;
    }

    public function setLore(?string $lore): static
    {
        $this->lore = $lore;

        return $this;
    }
}
