<?php

namespace App\Entity;

use App\Repository\ParagraphSpellRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphSpellRepository::class)]
class ParagraphSpell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'paragraphSpells')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    #[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'paragraphSpells')]
    #[ORM\JoinColumn(nullable: false)]
    private $spell;

    #[ORM\ManyToOne(targetEntity: ParagraphSpellCategory::class, inversedBy: 'paragraphSpells')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphspellcategory;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSpell(): ?Spell
    {
        return $this->spell;
    }

    public function setSpell(?Spell $spell): self
    {
        $this->spell = $spell;

        return $this;
    }

    public function getParagraphspellcategory(): ?ParagraphSpellCategory
    {
        return $this->paragraphspellcategory;
    }

    public function setParagraphspellcategory(?ParagraphSpellCategory $paragraphspellcategory): self
    {
        $this->paragraphspellcategory = $paragraphspellcategory;

        return $this;
    }
}
