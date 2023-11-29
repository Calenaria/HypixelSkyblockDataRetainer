<?php

namespace App\Entity;

use App\Repository\SkyblockItemSubcategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkyblockItemSubcategoryRepository::class)]
class SkyblockItemSubcategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'skyblockItemSubcategories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SkyblockItemCategory $superCategoryId = null;

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

    public function getSuperCategoryId(): ?SkyblockItemCategory
    {
        return $this->superCategoryId;
    }

    public function setSuperCategoryId(?SkyblockItemCategory $superCategoryId): self
    {
        $this->superCategoryId = $superCategoryId;

        return $this;
    }
}
