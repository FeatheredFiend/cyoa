<?php

namespace App\Entity;

use App\Repository\ParagraphActionSpellRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphActionSpellRepository::class)]
class ParagraphActionSpell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ParagraphAction::class, inversedBy: 'paragraphActionSpells')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphaction;

    #[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'paragraphActionSpells')]
    #[ORM\JoinColumn(nullable: false)]
    private $spell;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParagraphaction(): ?ParagraphAction
    {
        return $this->paragraphaction;
    }

    public function setParagraphaction(?ParagraphAction $paragraphaction): self
    {
        $this->paragraphaction = $paragraphaction;

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
