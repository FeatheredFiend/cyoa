<?php

namespace App\Entity;

use App\Repository\HeroSpellRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroSpellRepository::class)]
class HeroSpell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Hero::class, inversedBy: 'heroSpells')]
    #[ORM\JoinColumn(nullable: false)]
    private $hero;

    #[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'heroSpells')]
    #[ORM\JoinColumn(nullable: false)]
    private $spell;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHero(): ?Hero
    {
        return $this->hero;
    }

    public function setHero(?Hero $hero): self
    {
        $this->hero = $hero;

        return $this;
    }

    public function getSpell(): ?Spell
    {
        return $this->spell;
    }

    public function setSpell(?Spell $spell): self
    {
        $this->spell = $spell;

        return $this;
    }
}
