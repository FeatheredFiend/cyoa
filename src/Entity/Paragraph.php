<?php

namespace App\Entity;

use App\Repository\ParagraphRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphRepository::class)]
class Paragraph
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $number;

    #[ORM\Column(type: 'string', length: 5000)]
    private $text;

    #[ORM\OneToMany(mappedBy: 'paragraph', targetEntity: ParagraphAction::class)]
    private $paragraphActions;

    #[ORM\OneToMany(mappedBy: 'paragraph', targetEntity: GamebookParagraph::class)]
    private $gamebookParagraphs;

    #[ORM\OneToMany(mappedBy: 'paragraph', targetEntity: Equipment::class)]
    private $equipment;

    #[ORM\OneToMany(mappedBy: 'paragraph', targetEntity: Enemy::class)]
    private $enemies;

    public function __construct()
    {
        $this->paragraphActions = new ArrayCollection();
        $this->gamebookParagraphs = new ArrayCollection();
        $this->equipment = new ArrayCollection();
        $this->enemies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

    /**
     * @return Collection<int, ParagraphAction>
     */
    public function getParagraphActions(): Collection
    {
        return $this->paragraphActions;
    }

    public function addParagraphAction(ParagraphAction $paragraphAction): self
    {
        if (!$this->paragraphActions->contains($paragraphAction)) {
            $this->paragraphActions[] = $paragraphAction;
            $paragraphAction->setParagraph($this);
        }

        return $this;
    }

    public function removeParagraphAction(ParagraphAction $paragraphAction): self
    {
        if ($this->paragraphActions->removeElement($paragraphAction)) {
            // set the owning side to null (unless already changed)
            if ($paragraphAction->getParagraph() === $this) {
                $paragraphAction->setParagraph(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GamebookParagraph>
     */
    public function getGamebookParagraphs(): Collection
    {
        return $this->gamebookParagraphs;
    }

    public function addGamebookParagraph(GamebookParagraph $gamebookParagraph): self
    {
        if (!$this->gamebookParagraphs->contains($gamebookParagraph)) {
            $this->gamebookParagraphs[] = $gamebookParagraph;
            $gamebookParagraph->setParagraph($this);
        }

        return $this;
    }

    public function removeGamebookParagraph(GamebookParagraph $gamebookParagraph): self
    {
        if ($this->gamebookParagraphs->removeElement($gamebookParagraph)) {
            // set the owning side to null (unless already changed)
            if ($gamebookParagraph->getParagraph() === $this) {
                $gamebookParagraph->setParagraph(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipment(): Collection
    {
        return $this->equipment;
    }

    public function addEquipment(Equipment $equipment): self
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment[] = $equipment;
            $equipment->setParagraph($this);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): self
    {
        if ($this->equipment->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getParagraph() === $this) {
                $equipment->setParagraph(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Enemy>
     */
    public function getEnemies(): Collection
    {
        return $this->enemies;
    }

    public function addEnemy(Enemy $enemy): self
    {
        if (!$this->enemies->contains($enemy)) {
            $this->enemies[] = $enemy;
            $enemy->setParagraph($this);
        }

        return $this;
    }

    public function removeEnemy(Enemy $enemy): self
    {
        if ($this->enemies->removeElement($enemy)) {
            // set the owning side to null (unless already changed)
            if ($enemy->getParagraph() === $this) {
                $enemy->setParagraph(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->number;
    }

}
