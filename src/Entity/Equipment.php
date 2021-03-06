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

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: MerchantInventory::class)]
    private $merchantInventories;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: ParagraphActionEquipmentRequired::class)]
    private $paragraphActionEquipmentRequireds;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: ParagraphDirectionEquipmentRequired::class)]
    private $paragraphDirectionEquipmentRequireds;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: ParagraphEquipment::class)]
    private $paragraphEquipment;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: MagicEquipment::class)]
    private $magicEquipment;

    public function __construct()
    {
        $this->heroEquipment = new ArrayCollection();
        $this->equipmentEffects = new ArrayCollection();
        $this->merchantInventories = new ArrayCollection();
        $this->paragraphActionEquipmentRequireds = new ArrayCollection();
        $this->paragraphDirectionEquipmentRequireds = new ArrayCollection();
        $this->paragraphEquipment = new ArrayCollection();
        $this->magicEquipment = new ArrayCollection();
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

    /**
     * @return Collection<int, ParagraphActionEquipmentRequired>
     */
    public function getParagraphActionEquipmentRequireds(): Collection
    {
        return $this->paragraphActionEquipmentRequireds;
    }

    public function addParagraphActionEquipmentRequired(ParagraphActionEquipmentRequired $paragraphActionEquipmentRequired): self
    {
        if (!$this->paragraphActionEquipmentRequireds->contains($paragraphActionEquipmentRequired)) {
            $this->paragraphActionEquipmentRequireds[] = $paragraphActionEquipmentRequired;
            $paragraphActionEquipmentRequired->setEquipment($this);
        }

        return $this;
    }

    public function removeParagraphActionEquipmentRequired(ParagraphActionEquipmentRequired $paragraphActionEquipmentRequired): self
    {
        if ($this->paragraphActionEquipmentRequireds->removeElement($paragraphActionEquipmentRequired)) {
            // set the owning side to null (unless already changed)
            if ($paragraphActionEquipmentRequired->getEquipment() === $this) {
                $paragraphActionEquipmentRequired->setEquipment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ParagraphDirectionEquipmentRequired>
     */
    public function getParagraphDirectionEquipmentRequireds(): Collection
    {
        return $this->paragraphDirectionEquipmentRequireds;
    }

    public function addParagraphDirectionEquipmentRequired(ParagraphDirectionEquipmentRequired $paragraphDirectionEquipmentRequired): self
    {
        if (!$this->paragraphDirectionEquipmentRequireds->contains($paragraphDirectionEquipmentRequired)) {
            $this->paragraphDirectionEquipmentRequireds[] = $paragraphDirectionEquipmentRequired;
            $paragraphDirectionEquipmentRequired->setEquipment($this);
        }

        return $this;
    }

    public function removeParagraphDirectionEquipmentRequired(ParagraphDirectionEquipmentRequired $paragraphDirectionEquipmentRequired): self
    {
        if ($this->paragraphDirectionEquipmentRequireds->removeElement($paragraphDirectionEquipmentRequired)) {
            // set the owning side to null (unless already changed)
            if ($paragraphDirectionEquipmentRequired->getEquipment() === $this) {
                $paragraphDirectionEquipmentRequired->setEquipment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ParagraphEquipment>
     */
    public function getParagraphEquipment(): Collection
    {
        return $this->paragraphEquipment;
    }

    public function addParagraphEquipment(ParagraphEquipment $paragraphEquipment): self
    {
        if (!$this->paragraphEquipment->contains($paragraphEquipment)) {
            $this->paragraphEquipment[] = $paragraphEquipment;
            $paragraphEquipment->setEquipment($this);
        }

        return $this;
    }

    public function removeParagraphEquipment(ParagraphEquipment $paragraphEquipment): self
    {
        if ($this->paragraphEquipment->removeElement($paragraphEquipment)) {
            // set the owning side to null (unless already changed)
            if ($paragraphEquipment->getEquipment() === $this) {
                $paragraphEquipment->setEquipment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MagicEquipment>
     */
    public function getMagicEquipment(): Collection
    {
        return $this->magicEquipment;
    }

    public function addMagicEquipment(MagicEquipment $magicEquipment): self
    {
        if (!$this->magicEquipment->contains($magicEquipment)) {
            $this->magicEquipment[] = $magicEquipment;
            $magicEquipment->setEquipment($this);
        }

        return $this;
    }

    public function removeMagicEquipment(MagicEquipment $magicEquipment): self
    {
        if ($this->magicEquipment->removeElement($magicEquipment)) {
            // set the owning side to null (unless already changed)
            if ($magicEquipment->getEquipment() === $this) {
                $magicEquipment->setEquipment(null);
            }
        }

        return $this;
    }
}
