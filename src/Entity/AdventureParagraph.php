<?php

namespace App\Entity;

use App\Repository\AdventureParagraphRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdventureParagraphRepository::class)]
class AdventureParagraph
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Adventure::class, inversedBy: 'adventureParagraphs')]
    #[ORM\JoinColumn(nullable: false)]
    private $adventure;


    #[ORM\OneToMany(mappedBy: 'adventureparagraph', targetEntity: Battle::class)]
    private $battles;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'adventureParagraphs')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    public function __construct()
    {
        $this->battles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdventure(): ?Adventure
    {
        return $this->adventure;
    }

    public function setAdventure(?Adventure $adventure): self
    {
        $this->adventure = $adventure;

        return $this;
    }

    /**
     * @return Collection<int, Battle>
     */
    public function getBattles(): Collection
    {
        return $this->battles;
    }

    public function addBattle(Battle $battle): self
    {
        if (!$this->battles->contains($battle)) {
            $this->battles[] = $battle;
            $battle->setAdventureparagraph($this);
        }

        return $this;
    }

    public function removeBattle(Battle $battle): self
    {
        if ($this->battles->removeElement($battle)) {
            // set the owning side to null (unless already changed)
            if ($battle->getAdventureparagraph() === $this) {
                $battle->setAdventureparagraph(null);
            }
        }

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
