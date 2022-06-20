<?php

namespace App\Entity;

use App\Repository\ParagraphDirectionSpellRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphDirectionSpellRepository::class)]
class ParagraphDirectionSpell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ParagraphDirection::class, inversedBy: 'paragraphDirectionSpells')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphdirection;

    #[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'paragraphDirectionSpells')]
    #[ORM\JoinColumn(nullable: false)]
    private $spell;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParagraphdirection(): ?ParagraphDirection
    {
        return $this->paragraphdirection;
    }

    public function setParagraphdirection(?ParagraphDirection $paragraphdirection): self
    {
        $this->paragraphdirection = $paragraphdirection;

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
