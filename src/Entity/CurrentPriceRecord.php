<?php

namespace App\Entity;

use App\Repository\CurrentPriceRecordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrentPriceRecordRepository::class)]
class CurrentPriceRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $productId = null;

    #[ORM\Column]
    private ?float $sellPrice = null;

    #[ORM\Column]
    private ?int $sellVolume = null;

    #[ORM\Column]
    private ?int $sellMovingWeek = null;

    #[ORM\Column]
    private ?int $sellOrders = null;

    #[ORM\Column]
    private ?float $buyPrice = null;

    #[ORM\Column]
    private ?int $buyVolume = null;

    #[ORM\Column]
    private ?int $buyMovingWeek = null;

    #[ORM\Column]
    private ?int $buyOrders = null;

    #[ORM\Column]
    private ?string $recordDate = null;

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

    public function getSellPrice(): ?float
    {
        return $this->sellPrice;
    }

    public function setSellPrice(float $sellPrice): self
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    public function getSellVolume(): ?int
    {
        return $this->sellVolume;
    }

    public function setSellVolume(int $sellVolume): self
    {
        $this->sellVolume = $sellVolume;

        return $this;
    }

    public function getSellMovingWeek(): ?int
    {
        return $this->sellMovingWeek;
    }

    public function setSellMovingWeek(int $sellMovingWeek): self
    {
        $this->sellMovingWeek = $sellMovingWeek;

        return $this;
    }

    public function getSellOrders(): ?int
    {
        return $this->sellOrders;
    }

    public function setSellOrders(int $sellOrders): self
    {
        $this->sellOrders = $sellOrders;

        return $this;
    }

    public function getBuyPrice(): ?float
    {
        return $this->buyPrice;
    }

    public function setBuyPrice(float $buyPrice): self
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    public function getBuyVolume(): ?int
    {
        return $this->buyVolume;
    }

    public function setBuyVolume(int $buyVolume): self
    {
        $this->buyVolume = $buyVolume;

        return $this;
    }

    public function getBuyMovingWeek(): ?int
    {
        return $this->buyMovingWeek;
    }

    public function setBuyMovingWeek(int $buyMovingWeek): self
    {
        $this->buyMovingWeek = $buyMovingWeek;

        return $this;
    }

    public function getBuyOrders(): ?int
    {
        return $this->buyOrders;
    }

    public function setBuyOrders(int $buyOrders): self
    {
        $this->buyOrders = $buyOrders;

        return $this;
    }

    public function getRecordDate(): ?string
    {
        return $this->recordDate;
    }

    public function getFormattedRecordDate(): string {
        return date('d.m.Y H:i:s', $this->recordDate ?? 0);
    }

    public function setRecordDate(string $recordDate): self
    {
        $this->recordDate = $recordDate;

        return $this;
    }
}
