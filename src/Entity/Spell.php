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

    #[ORM\OneToMany(mappedBy: 'spell', targetEntity: ParagraphSpell::class)]
    private $paragraphSpells;

    #[ORM\OneToMany(mappedBy: 'spell', targetEntity: ParagraphActionSpell::class)]
    private $paragraphActionSpells;

    #[ORM\OneToMany(mappedBy: 'spell', targetEntity: ParagraphDirectionSpell::class)]
    private $paragraphDirectionSpells;

    public function __construct()
    {
        $this->heroSpells = new ArrayCollection();
        $this->paragraphSpells = new ArrayCollection();
        $this->paragraphActionSpells = new ArrayCollection();
        $this->paragraphDirectionSpells = new ArrayCollection();
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

    public function __toString() {
        return $this->name;
    }

    /**
     * @return Collection<int, ParagraphSpell>
     */
    public function getParagraphSpells(): Collection
    {
        return $this->paragraphSpells;
    }

    public function addParagraphSpell(ParagraphSpell $paragraphSpell): self
    {
        if (!$this->paragraphSpells->contains($paragraphSpell)) {
            $this->paragraphSpells[] = $paragraphSpell;
            $paragraphSpell->setSpell($this);
        }

        return $this;
    }

    public function removeParagraphSpell(ParagraphSpell $paragraphSpell): self
    {
        if ($this->paragraphSpells->removeElement($paragraphSpell)) {
            // set the owning side to null (unless already changed)
            if ($paragraphSpell->getSpell() === $this) {
                $paragraphSpell->setSpell(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ParagraphActionSpell>
     */
    public function getParagraphActionSpells(): Collection
    {
        return $this->paragraphActionSpells;
    }

    public function addParagraphActionSpell(ParagraphActionSpell $paragraphActionSpell): self
    {
        if (!$this->paragraphActionSpells->contains($paragraphActionSpell)) {
            $this->paragraphActionSpells[] = $paragraphActionSpell;
            $paragraphActionSpell->setSpell($this);
        }

        return $this;
    }

    public function removeParagraphActionSpell(ParagraphActionSpell $paragraphActionSpell): self
    {
        if ($this->paragraphActionSpells->removeElement($paragraphActionSpell)) {
            // set the owning side to null (unless already changed)
            if ($paragraphActionSpell->getSpell() === $this) {
                $paragraphActionSpell->setSpell(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ParagraphDirectionSpell>
     */
    public function getParagraphDirectionSpells(): Collection
    {
        return $this->paragraphDirectionSpells;
    }

    public function addParagraphDirectionSpell(ParagraphDirectionSpell $paragraphDirectionSpell): self
    {
        if (!$this->paragraphDirectionSpells->contains($paragraphDirectionSpell)) {
            $this->paragraphDirectionSpells[] = $paragraphDirectionSpell;
            $paragraphDirectionSpell->setSpell($this);
        }

        return $this;
    }

    public function removeParagraphDirectionSpell(ParagraphDirectionSpell $paragraphDirectionSpell): self
    {
        if ($this->paragraphDirectionSpells->removeElement($paragraphDirectionSpell)) {
            // set the owning side to null (unless already changed)
            if ($paragraphDirectionSpell->getSpell() === $this) {
                $paragraphDirectionSpell->setSpell(null);
            }
        }

        return $this;
    }
}
