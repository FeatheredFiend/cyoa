<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: HeroEquipment::class)]
    private $heroEquipment;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: EquipmentEffect::class)]
    private $equipmentEffects;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: EquipmentRequired::class)]
    private $equipmentRequireds;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: MerchantInventory::class)]
    private $merchantInventories;

    public function __construct()
    {
        $this->heroEquipment = new ArrayCollection();
        $this->equipmentEffects = new ArrayCollection();
        $this->equipmentRequireds = new ArrayCollection();
        $this->merchantInventories = new ArrayCollection();
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
     * @return Collection<int, HeroEquipment>
     */
    public function getHeroEquipment(): Collection
    {
        return $this->heroEquipment;
    }

    public function addHeroEquipment(HeroEquipment $heroEquipment): self
    {
        if (!$this->heroEquipment->contains($heroEquipment)) {
            $this->heroEquipment[] = $heroEquipment;
            $heroEquipment->setEquipment($this);
        }

        return $this;
    }

    public function removeHeroEquipment(HeroEquipment $heroEquipment): self
    {
        if ($this->heroEquipment->removeElement($heroEquipment)) {
            // set the owning side to null (unless already changed)
            if ($heroEquipment->getEquipment() === $this) {
                $heroEquipment->setEquipment(null);
            }
        }

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
            $equipmentEffect->setEquipment($this);
        }

        return $this;
    }

    public function removeEquipmentEffect(EquipmentEffect $equipmentEffect): self
    {
        if ($this->equipmentEffects->removeElement($equipmentEffect)) {
            // set the owning side to null (unless already changed)
            if ($equipmentEffect->getEquipment() === $this) {
                $equipmentEffect->setEquipment(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * @return Collection<int, EquipmentRequired>
     */
    public function getEquipmentRequireds(): Collection
    {
        return $this->equipmentRequireds;
    }

    public function addEquipmentRequired(EquipmentRequired $equipmentRequired): self
    {
        if (!$this->equipmentRequireds->contains($equipmentRequired)) {
            $this->equipmentRequireds[] = $equipmentRequired;
            $equipmentRequired->setEquipment($this);
        }

        return $this;
    }

    public function removeEquipmentRequired(EquipmentRequired $equipmentRequired): self
    {
        if ($this->equipmentRequireds->removeElement($equipmentRequired)) {
            // set the owning side to null (unless already changed)
            if ($equipmentRequired->getEquipment() === $this) {
                $equipmentRequired->setEquipment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MerchantInventory>
     */
    public function getMerchantInventories(): Collection
    {
        return $this->merchantInventories;
    }

    public function addMerchantInventory(MerchantInventory $merchantInventory): self
    {
        if (!$this->merchantInventories->contains($merchantInventory)) {
            $this->merchantInventories[] = $merchantInventory;
            $merchantInventory->setEquipment($this);
        }

        return $this;
    }

    public function removeMerchantInventory(MerchantInventory $merchantInventory): self
    {
        if ($this->merchantInventories->removeElement($merchantInventory)) {
            // set the owning side to null (unless already changed)
            if ($merchantInventory->getEquipment() === $this) {
                $merchantInventory->setEquipment(null);
            }
        }

        return $this;
    }
}
