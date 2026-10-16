<?php

namespace App\Entity;

use App\Repository\ParagraphEquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphEquipmentRepository::class)]
class ParagraphEquipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'paragraphEquipment')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    #[ORM\ManyToOne(targetEntity: Equipment::class, inversedBy: 'paragraphEquipment')]
    #[ORM\JoinColumn(nullable: false)]
    private $equipment;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\ManyToOne(targetEntity: ParagraphEquipmentCategory::class, inversedBy: 'paragraphEquipment')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphequipmentcategory;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getParagraphequipmentcategory(): ?ParagraphEquipmentCategory
    {
        return $this->paragraphequipmentcategory;
    }

    public function setParagraphequipmentcategory(?ParagraphEquipmentCategory $paragraphequipmentcategory): self
    {
        $this->paragraphequipmentcategory = $paragraphequipmentcategory;

        return $this;
    }
}
