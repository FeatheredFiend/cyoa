<?php

namespace App\Entity;

use App\Repository\ParagraphActionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphActionRepository::class)]
class ParagraphAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'paragraphActions')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    #[ORM\ManyToOne(targetEntity: ParagraphActionCategory::class, inversedBy: 'paragraphActions')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphactioncategory;

    #[ORM\Column(type: 'string', length: 5000)]
    private $text;

    #[ORM\Column(type: 'integer')]
    private $actionvalue;

    #[ORM\ManyToOne(targetEntity: ParagraphActionOperator::class, inversedBy: 'paragraphActions')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphactionoperator;

    #[ORM\ManyToOne(targetEntity: HeroAttribute::class, inversedBy: 'paragraphActions')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphactionattribute;

    #[ORM\ManyToOne(targetEntity: ParagraphActionTarget::class, inversedBy: 'paragraphActions')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphactiontarget;

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

    public function getParagraphactioncategory(): ?ParagraphActionCategory
    {
        return $this->paragraphactioncategory;
    }

    public function setParagraphactioncategory(?ParagraphActionCategory $paragraphactioncategory): self
    {
        $this->paragraphactioncategory = $paragraphactioncategory;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getActionvalue(): ?int
    {
        return $this->actionvalue;
    }

    public function setActionvalue(int $actionvalue): self
    {
        $this->actionvalue = $actionvalue;

        return $this;
    }

    public function getParagraphactionoperator(): ?ParagraphActionOperator
    {
        return $this->paragraphactionoperator;
    }

    public function setParagraphactionoperator(?ParagraphActionOperator $paragraphactionoperator): self
    {
        $this->paragraphactionoperator = $paragraphactionoperator;

        return $this;
    }

    public function getParagraphactionattribute(): ?HeroAttribute
    {
        return $this->paragraphactionattribute;
    }

    public function setParagraphactionattribute(?HeroAttribute $paragraphactionattribute): self
    {
        $this->paragraphactionattribute = $paragraphactionattribute;

        return $this;
    }

    public function getParagraphactiontarget(): ?ParagraphActionTarget
    {
        return $this->paragraphactiontarget;
    }

    public function setParagraphactiontarget(?ParagraphActionTarget $paragraphactiontarget): self
    {
        $this->paragraphactiontarget = $paragraphactiontarget;

        return $this;
    }
}
