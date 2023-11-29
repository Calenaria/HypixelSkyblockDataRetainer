<?php

namespace App\Entity;

use App\Repository\ChangeLogEntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChangeLogEntryRepository::class)]
class ChangeLogEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $changeLogText = null;

    #[ORM\Column(length: 255)]
    private ?string $version = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $changeLogDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChangeLogText(): ?string
    {
        return $this->changeLogText;
    }

    public function setChangeLogText(string $changeLogText): self
    {
        $this->changeLogText = $changeLogText;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getChangeLogDate(): ?\DateTimeInterface
    {
        return $this->changeLogDate;
    }

    public function setChangeLogDate(\DateTimeInterface $changeLogDate): self
    {
        $this->changeLogDate = $changeLogDate;

        return $this;
    }

    public function __toString()
    {
        return $this->changeLogText;
    }
}
