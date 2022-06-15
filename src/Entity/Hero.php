<?php

namespace App\Entity;

use App\Repository\HeroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroRepository::class)]
class Hero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $skill;

    #[ORM\Column(type: 'integer')]
    private $stamina;

    #[ORM\Column(type: 'integer')]
    private $luck;

    #[ORM\Column(type: 'integer')]
    private $honour;

    #[ORM\Column(type: 'integer')]
    private $startingskill;

    #[ORM\Column(type: 'integer')]
    private $startingstamina;

    #[ORM\Column(type: 'integer')]
    private $startingluck;

    #[ORM\Column(type: 'integer')]
    private $startingprovision;

    #[ORM\Column(type: 'integer')]
    private $provision;

    #[ORM\OneToOne(mappedBy: 'hero', targetEntity: Adventure::class, cascade: ['persist', 'remove'])]
    private $adventure;

    #[ORM\OneToMany(mappedBy: 'hero', targetEntity: HeroEquipment::class)]
    private $heroEquipment;

    #[ORM\Column(type: 'integer')]
    private $treasure;

    #[ORM\OneToMany(mappedBy: 'hero', targetEntity: HeroSpell::class)]
    private $heroSpells;

    public function __construct()
    {
        $this->heroEquipment = new ArrayCollection();
        $this->heroSpells = new ArrayCollection();
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

    public function getSkill(): ?int
    {
        return $this->skill;
    }

    public function setSkill(int $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getStamina(): ?int
    {
        return $this->stamina;
    }

    public function setStamina(int $stamina): self
    {
        $this->stamina = $stamina;

        return $this;
    }

    public function getLuck(): ?int
    {
        return $this->luck;
    }

    public function setLuck(int $luck): self
    {
        $this->luck = $luck;

        return $this;
    }

    public function getHonour(): ?int
    {
        return $this->honour;
    }

    public function setHonour(int $honour): self
    {
        $this->honour = $honour;

        return $this;
    }

    public function getStartingskill(): ?int
    {
        return $this->startingskill;
    }

    public function setStartingskill(int $startingskill): self
    {
        $this->startingskill = $startingskill;

        return $this;
    }

    public function getStartingstamina(): ?int
    {
        return $this->startingstamina;
    }

    public function setStartingstamina(int $startingstamina): self
    {
        $this->startingstamina = $startingstamina;

        return $this;
    }

    public function getStartingluck(): ?int
    {
        return $this->startingluck;
    }

    public function setStartingluck(int $startingluck): self
    {
        $this->startingluck = $startingluck;

        return $this;
    }

    public function getStartingprovision(): ?int
    {
        return $this->startingprovision;
    }

    public function setStartingprovision(int $startingprovision): self
    {
        $this->startingprovision = $startingprovision;

        return $this;
    }

    public function getProvision(): ?int
    {
        return $this->provision;
    }

    public function setProvision(int $provision): self
    {
        $this->provision = $provision;

        return $this;
    }

    public function getAdventure(): ?Adventure
    {
        return $this->adventure;
    }

    public function setAdventure(Adventure $adventure): self
    {
        // set the owning side of the relation if necessary
        if ($adventure->getHero() !== $this) {
            $adventure->setHero($this);
        }

        $this->adventure = $adventure;

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
            $heroEquipment->setHero($this);
        }

        return $this;
    }

    public function removeHeroEquipment(HeroEquipment $heroEquipment): self
    {
        if ($this->heroEquipment->removeElement($heroEquipment)) {
            // set the owning side to null (unless already changed)
            if ($heroEquipment->getHero() === $this) {
                $heroEquipment->setHero(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }

    public function getTreasure(): ?int
    {
        return $this->treasure;
    }

    public function setTreasure(int $treasure): self
    {
        $this->treasure = $treasure;

        return $this;
    }

    /**
     * @return Collection<int, HeroSpell>
     */
    public function getHeroSpells(): Collection
    {
        return $this->heroSpells;
    }

    public function addHeroSpell(HeroSpell $heroSpell): self
    {
        if (!$this->heroSpells->contains($heroSpell)) {
            $this->heroSpells[] = $heroSpell;
            $heroSpell->setHero($this);
        }

        return $this;
    }

    public function removeHeroSpell(HeroSpell $heroSpell): self
    {
        if ($this->heroSpells->removeElement($heroSpell)) {
            // set the owning side to null (unless already changed)
            if ($heroSpell->getHero() === $this) {
                $heroSpell->setHero(null);
            }
        }

        return $this;
    }
}
