<?php

namespace App\Entity;

use App\Repository\ParagraphDirectionEquipmentRequiredRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphDirectionEquipmentRequiredRepository::class)]
class ParagraphDirectionEquipmentRequired
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ParagraphDirection::class, inversedBy: 'paragraphDirectionEquipmentRequireds')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphdirection;

    #[ORM\ManyToOne(targetEntity: Equipment::class, inversedBy: 'paragraphDirectionEquipmentRequireds')]
    #[ORM\JoinColumn(nullable: false)]
    private $equipment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParagraphdirection(): ?ParagraphDirection
    {
        return $this->paragraphdirection;
    }

    public function setParagraphdirection(?ParagraphDirection $paragraphdirection): self
    {
        $this->paragraphdirection = $paragraphdirection;

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
}
