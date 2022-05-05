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

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'equipment')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: HeroEquipment::class)]
    private $heroEquipment;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: EquipmentEffect::class)]
    private $equipmentEffects;

    public function __construct()
    {
        $this->heroEquipment = new ArrayCollection();
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

    public function getParagraph(): ?Paragraph
    {
        return $this->paragraph;
    }

    public function setParagraph(?Paragraph $paragraph): self
    {
        $this->paragraph = $paragraph;

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
}
