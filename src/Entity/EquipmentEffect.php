<?php

namespace App\Entity;

use App\Repository\EquipmentEffectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentEffectRepository::class)]
class EquipmentEffect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Equipment::class, inversedBy: 'equipmentEffects')]
    #[ORM\JoinColumn(nullable: false)]
    private $equipment;

    #[ORM\Column(type: 'integer')]
    private $equipmenteffectvalue;

    #[ORM\ManyToOne(targetEntity: EquipmentEffectOperator::class, inversedBy: 'equipmentEffects')]
    #[ORM\JoinColumn(nullable: false)]
    private $equipmenteffectoperator;

    #[ORM\ManyToOne(targetEntity: EquipmentEffectAttribute::class, inversedBy: 'equipmentEffects')]
    #[ORM\JoinColumn(nullable: false)]
    private $equipmenteffectattribute;

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

    public function getEquipmenteffectvalue(): ?int
    {
        return $this->equipmenteffectvalue;
    }

    public function setEquipmenteffectvalue(int $equipmenteffectvalue): self
    {
        $this->equipmenteffectvalue = $equipmenteffectvalue;

        return $this;
    }

    public function getEquipmenteffectoperator(): ?EquipmentEffectOperator
    {
        return $this->equipmenteffectoperator;
    }

    public function setEquipmenteffectoperator(?EquipmentEffectOperator $equipmenteffectoperator): self
    {
        $this->equipmenteffectoperator = $equipmenteffectoperator;

        return $this;
    }

    public function getEquipmenteffectattribute(): ?EquipmentEffectAttribute
    {
        return $this->equipmenteffectattribute;
    }

    public function setEquipmenteffectattribute(?EquipmentEffectAttribute $equipmenteffectattribute): self
    {
        $this->equipmenteffectattribute = $equipmenteffectattribute;

        return $this;
    }
}
