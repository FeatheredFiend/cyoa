<?php

namespace App\Entity;

use App\Repository\GamebookParagraphRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamebookParagraphRepository::class)]
class GamebookParagraph
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Gamebook::class, inversedBy: 'gamebookParagraphs')]
    #[ORM\JoinColumn(nullable: false)]
    private $gamebook;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'gamebookParagraphs')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamebook(): ?Gamebook
    {
        return $this->gamebook;
    }

    public function setGamebook(?Gamebook $gamebook): self
    {
        $this->gamebook = $gamebook;

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
}
