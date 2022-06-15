<?php

namespace App\Entity;

use App\Repository\MagicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagicRepository::class)]
class Magic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'magic', targetEntity: MagicEffect::class)]
    private $magicEffects;

    #[ORM\OneToMany(mappedBy: 'magic', targetEntity: Spell::class)]
    private $spells;

    #[ORM\OneToMany(mappedBy: 'magic', targetEntity: MagicEquipment::class)]
    private $magicEquipment;

    public function __construct()
    {
        $this->magicEffects = new ArrayCollection();
        $this->spells = new ArrayCollection();
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
     * @return Collection<int, MagicEffect>
     */
    public function getMagicEffects(): Collection
    {
        return $this->magicEffects;
    }

    public function addMagicEffect(MagicEffect $magicEffect): self
    {
        if (!$this->magicEffects->contains($magicEffect)) {
            $this->magicEffects[] = $magicEffect;
            $magicEffect->setMagic($this);
        }

        return $this;
    }

    public function removeMagicEffect(MagicEffect $magicEffect): self
    {
        if ($this->magicEffects->removeElement($magicEffect)) {
            // set the owning side to null (unless already changed)
            if ($magicEffect->getMagic() === $this) {
                $magicEffect->setMagic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Spell>
     */
    public function getSpells(): Collection
    {
        return $this->spells;
    }

    public function addSpell(Spell $spell): self
    {
        if (!$this->spells->contains($spell)) {
            $this->spells[] = $spell;
            $spell->setMagic($this);
        }

        return $this;
    }

    public function removeSpell(Spell $spell): self
    {
        if ($this->spells->removeElement($spell)) {
            // set the owning side to null (unless already changed)
            if ($spell->getMagic() === $this) {
                $spell->setMagic(null);
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
            $magicEquipment->setMagic($this);
        }

        return $this;
    }

    public function removeMagicEquipment(MagicEquipment $magicEquipment): self
    {
        if ($this->magicEquipment->removeElement($magicEquipment)) {
            // set the owning side to null (unless already changed)
            if ($magicEquipment->getMagic() === $this) {
                $magicEquipment->setMagic(null);
            }
        }

        return $this;
    }
}
