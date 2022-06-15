<?php

namespace App\Entity;

use App\Repository\SpellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpellRepository::class)]
class Spell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Magic::class, inversedBy: 'spells')]
    #[ORM\JoinColumn(nullable: false)]
    private $magic;

    #[ORM\OneToMany(mappedBy: 'spell', targetEntity: HeroSpell::class)]
    private $heroSpells;

    public function __construct()
    {
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

    public function getMagic(): ?Magic
    {
        return $this->magic;
    }

    public function setMagic(?Magic $magic): self
    {
        $this->magic = $magic;

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
            $heroSpell->setSpell($this);
        }

        return $this;
    }

    public function removeHeroSpell(HeroSpell $heroSpell): self
    {
        if ($this->heroSpells->removeElement($heroSpell)) {
            // set the owning side to null (unless already changed)
            if ($heroSpell->getSpell() === $this) {
                $heroSpell->setSpell(null);
            }
        }

        return $this;
    }
}
