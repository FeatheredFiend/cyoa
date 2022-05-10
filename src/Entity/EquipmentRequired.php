<?php

namespace App\Entity;

use App\Repository\EquipmentRequiredRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRequiredRepository::class)]
class EquipmentRequired
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Equipment::class, inversedBy: 'equipmentRequireds')]
    #[ORM\JoinColumn(nullable: false)]
    private $equipment;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'equipmentRequireds')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getParagraph(): ?Paragraph
    {
        return $this->paragraph;
    }

    public function setParagraph(?Paragraph $paragraph): self
    {
        $this->paragraph = $paragraph;

        return $this;
    }
}
