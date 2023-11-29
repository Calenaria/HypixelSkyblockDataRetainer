<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SkyblockAuctionItemRepository;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: SkyblockAuctionItemRepository::class)]
class SkyblockAuctionItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $uuid = null;

    #[ORM\Column(nullable: true)]
    private ?string $itemUuid = null;

    #[ORM\Column]
    private ?bool $bin = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $startingBid = null;

    #[ORM\Column]
    private ?string $auctioneer = null;

    #[ORM\Column(length: 255)]
    private ?string $itemName = null;

    #[ORM\Column(length: 255)]
    private ?string $rarity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getItemUuid(): ?string
    {
        return $this->itemUuid;
    }

    public function setItemUuid(?string $itemUuid): self
    {
        $this->itemUuid = $itemUuid;

        return $this;
    }

    public function isBin(): ?bool
    {
        return $this->bin;
    }

    public function setBin(bool $bin): self
    {
        $this->bin = $bin;

        return $this;
    }

    public function getStartingBid(): ?string
    {
        return $this->startingBid;
    }

    public function setStartingBid(string $startingBid): self
    {
        $this->startingBid = $startingBid;

        return $this;
    }

    public function getAuctioneer(): ?string
    {
        return $this->auctioneer;
    }

    public function setAuctioneer(string $auctioneer): self
    {
        $this->auctioneer = $auctioneer;

        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(string $itemName): self
    {
        $this->itemName = $itemName;

        return $this;
    }

    public function getRarity(): ?string
    {
        return $this->rarity;
    }

    public function setRarity(string $rarity): static
    {
        $this->rarity = $rarity;

        return $this;
    }
}
