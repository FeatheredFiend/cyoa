<?php

namespace App\Entity;

use App\Repository\GamebookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamebookRepository::class)]
class Gamebook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'gamebook', targetEntity: Adventure::class)]
    private $adventures;

    #[ORM\OneToMany(mappedBy: 'gamebook', targetEntity: Paragraph::class)]
    private $paragraphs;

    public function __construct()
    {
        $this->adventures = new ArrayCollection();
        $this->paragraphs = new ArrayCollection();
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

    /**
     * @return Collection<int, Adventure>
     */
    public function getAdventures(): Collection
    {
        return $this->adventures;
    }

    public function addAdventure(Adventure $adventure): self
    {
        if (!$this->adventures->contains($adventure)) {
            $this->adventures[] = $adventure;
            $adventure->setGamebook($this);
        }

        return $this;
    }

    public function removeAdventure(Adventure $adventure): self
    {
        if ($this->adventures->removeElement($adventure)) {
            // set the owning side to null (unless already changed)
            if ($adventure->getGamebook() === $this) {
                $adventure->setGamebook(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * @return Collection<int, Paragraph>
     */
    public function getParagraphs(): Collection
    {
        return $this->paragraphs;
    }

    public function addParagraph(Paragraph $paragraph): self
    {
        if (!$this->paragraphs->contains($paragraph)) {
            $this->paragraphs[] = $paragraph;
            $paragraph->setGamebook($this);
        }

        return $this;
    }

    public function removeParagraph(Paragraph $paragraph): self
    {
        if ($this->paragraphs->removeElement($paragraph)) {
            // set the owning side to null (unless already changed)
            if ($paragraph->getGamebook() === $this) {
                $paragraph->setGamebook(null);
            }
        }

        return $this;
    }
}
