<?php

namespace App\Entity;

use App\Repository\MagicEffectAttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagicEffectAttributeRepository::class)]
class MagicEffectAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'magiceffectattribute', targetEntity: MagicEffect::class)]
    private $magicEffects;

    public function __construct()
    {
        $this->magicEffects = new ArrayCollection();
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
            $magicEffect->setMagiceffectattribute($this);
        }

        return $this;
    }

    public function removeMagicEffect(MagicEffect $magicEffect): self
    {
        if ($this->magicEffects->removeElement($magicEffect)) {
            // set the owning side to null (unless already changed)
            if ($magicEffect->getMagiceffectattribute() === $this) {
                $magicEffect->setMagiceffectattribute(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
