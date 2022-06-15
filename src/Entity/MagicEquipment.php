<?php

namespace App\Entity;

use App\Repository\MagicEquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagicEquipmentRepository::class)]
class MagicEquipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Magic::class, inversedBy: 'magicEquipment')]
    #[ORM\JoinColumn(nullable: false)]
    private $magic;

    #[ORM\ManyToOne(targetEntity: Equipment::class, inversedBy: 'magicEquipment')]
    #[ORM\JoinColumn(nullable: false)]
    private $equipment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMagic(): ?Magic
    {
        return $this->magic;
    }

    public function setMagic(?Magic $magic): self
    {
        $this->magic = $magic;

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
