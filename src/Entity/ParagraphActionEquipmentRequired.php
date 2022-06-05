<?php

namespace App\Entity;

use App\Repository\ParagraphActionEquipmentRequiredRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphActionEquipmentRequiredRepository::class)]
class ParagraphActionEquipmentRequired
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ParagraphAction::class, inversedBy: 'paragraphActionEquipmentRequireds')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphaction;

    #[ORM\ManyToOne(targetEntity: Equipment::class, inversedBy: 'paragraphActionEquipmentRequireds')]
    #[ORM\JoinColumn(nullable: false)]
    private $equipment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParagraphaction(): ?ParagraphAction
    {
        return $this->paragraphaction;
    }

    public function setParagraphaction(?ParagraphAction $paragraphaction): self
    {
        $this->paragraphaction = $paragraphaction;

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
