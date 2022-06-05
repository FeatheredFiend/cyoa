<?php

namespace App\Entity;

use App\Repository\ParagraphDirectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphDirectionRepository::class)]
class ParagraphDirection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 500)]
    private $text;

    #[ORM\Column(type: 'integer')]
    private $maxaccess;

    #[ORM\Column(type: 'integer')]
    private $redirectparagraph;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'paragraphDirections')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    #[ORM\OneToMany(mappedBy: 'paragraphdirection', targetEntity: ParagraphDirectionEquipmentRequired::class)]
    private $paragraphDirectionEquipmentRequireds;

    public function __construct()
    {
        $this->paragraphDirectionEquipmentRequireds = new ArrayCollection();
    }

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getMaxaccess(): ?int
    {
        return $this->maxaccess;
    }

    public function setMaxaccess(int $maxaccess): self
    {
        $this->maxaccess = $maxaccess;

        return $this;
    }

    public function getRedirectparagraph(): ?int
    {
        return $this->redirectparagraph;
    }

    public function setRedirectparagraph(int $redirectparagraph): self
    {
        $this->redirectparagraph = $redirectparagraph;

        return $this;
    }

    /**
     * @return Collection<int, ParagraphDirectionEquipmentRequired>
     */
    public function getParagraphDirectionEquipmentRequireds(): Collection
    {
        return $this->paragraphDirectionEquipmentRequireds;
    }

    public function addParagraphDirectionEquipmentRequired(ParagraphDirectionEquipmentRequired $paragraphDirectionEquipmentRequired): self
    {
        if (!$this->paragraphDirectionEquipmentRequireds->contains($paragraphDirectionEquipmentRequired)) {
            $this->paragraphDirectionEquipmentRequireds[] = $paragraphDirectionEquipmentRequired;
            $paragraphDirectionEquipmentRequired->setParagraphdirection($this);
        }

        return $this;
    }

    public function removeParagraphDirectionEquipmentRequired(ParagraphDirectionEquipmentRequired $paragraphDirectionEquipmentRequired): self
    {
        if ($this->paragraphDirectionEquipmentRequireds->removeElement($paragraphDirectionEquipmentRequired)) {
            // set the owning side to null (unless already changed)
            if ($paragraphDirectionEquipmentRequired->getParagraphdirection() === $this) {
                $paragraphDirectionEquipmentRequired->setParagraphdirection(null);
            }
        }

        return $this;
    }

}
