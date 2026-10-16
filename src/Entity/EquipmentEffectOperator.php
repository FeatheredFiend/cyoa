<?php

namespace App\Entity;

use App\Repository\EquipmentEffectOperatorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentEffectOperatorRepository::class)]
class EquipmentEffectOperator
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'equipmenteffectoperator', targetEntity: EquipmentEffect::class)]
    private $equipmentEffects;

    public function __construct()
    {
        $this->equipmentEffects = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, EquipmentEffect>
     */
    public function getEquipmentEffects(): Collection
    {
        return $this->equipmentEffects;
    }

    public function addEquipmentEffect(EquipmentEffect $equipmentEffect): self
    {
        if (!$this->equipmentEffects->contains($equipmentEffect)) {
            $this->equipmentEffects[] = $equipmentEffect;
            $equipmentEffect->setEquipmenteffectoperator($this);
        }

        return $this;
    }

    public function removeEquipmentEffect(EquipmentEffect $equipmentEffect): self
    {
        if ($this->equipmentEffects->removeElement($equipmentEffect)) {
            // set the owning side to null (unless already changed)
            if ($equipmentEffect->getEquipmenteffectoperator() === $this) {
                $equipmentEffect->setEquipmenteffectoperator(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
