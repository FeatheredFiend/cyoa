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

    #[ORM\OneToMany(mappedBy: 'paragraph', targetEntity: Enemy::class)]
    private $enemies;

    #[ORM\OneToMany(mappedBy: 'paragraph', targetEntity: ParagraphDirection::class)]
    private $paragraphDirections;

    #[ORM\ManyToOne(targetEntity: Gamebook::class, inversedBy: 'paragraphs')]
    #[ORM\JoinColumn(nullable: false)]
    private $gamebook;

    #[ORM\OneToMany(mappedBy: 'paragraph', targetEntity: EquipmentRequired::class)]
    private $equipmentRequireds;

    #[ORM\OneToMany(mappedBy: 'paragraph', targetEntity: Merchant::class)]
    private $merchants;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\OneToMany(mappedBy: 'paragraph', targetEntity: AdventureParagraph::class)]
    private $adventureParagraphs;

    public function __construct()
    {
        $this->paragraphActions = new ArrayCollection();
        $this->equipment = new ArrayCollection();
        $this->enemies = new ArrayCollection();
        $this->paragraphDirections = new ArrayCollection();
        $this->equipmentRequireds = new ArrayCollection();
        $this->merchants = new ArrayCollection();
        $this->adventureParagraphs = new ArrayCollection();
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

    /**
     * @return Collection<int, ParagraphDirection>
     */
    public function getParagraphDirections(): Collection
    {
        return $this->paragraphDirections;
    }

    public function addParagraphDirection(ParagraphDirection $paragraphDirection): self
    {
        if (!$this->paragraphDirections->contains($paragraphDirection)) {
            $this->paragraphDirections[] = $paragraphDirection;
            $paragraphDirection->setParagraph($this);
        }

        return $this;
    }

    public function removeParagraphDirection(ParagraphDirection $paragraphDirection): self
    {
        if ($this->paragraphDirections->removeElement($paragraphDirection)) {
            // set the owning side to null (unless already changed)
            if ($paragraphDirection->getParagraph() === $this) {
                $paragraphDirection->setParagraph(null);
            }
        }

        return $this;
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

    /**
     * @return Collection<int, EquipmentRequired>
     */
    public function getEquipmentRequireds(): Collection
    {
        return $this->equipmentRequireds;
    }

    public function addEquipmentRequired(EquipmentRequired $equipmentRequired): self
    {
        if (!$this->equipmentRequireds->contains($equipmentRequired)) {
            $this->equipmentRequireds[] = $equipmentRequired;
            $equipmentRequired->setParagraph($this);
        }

        return $this;
    }

    public function removeEquipmentRequired(EquipmentRequired $equipmentRequired): self
    {
        if ($this->equipmentRequireds->removeElement($equipmentRequired)) {
            // set the owning side to null (unless already changed)
            if ($equipmentRequired->getParagraph() === $this) {
                $equipmentRequired->setParagraph(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Merchant>
     */
    public function getMerchants(): Collection
    {
        return $this->merchants;
    }

    public function addMerchant(Merchant $merchant): self
    {
        if (!$this->merchants->contains($merchant)) {
            $this->merchants[] = $merchant;
            $merchant->setParagraph($this);
        }

        return $this;
    }

    public function removeMerchant(Merchant $merchant): self
    {
        if ($this->merchants->removeElement($merchant)) {
            // set the owning side to null (unless already changed)
            if ($merchant->getParagraph() === $this) {
                $merchant->setParagraph(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, AdventureParagraph>
     */
    public function getAdventureParagraphs(): Collection
    {
        return $this->adventureParagraphs;
    }

    public function addAdventureParagraph(AdventureParagraph $adventureParagraph): self
    {
        if (!$this->adventureParagraphs->contains($adventureParagraph)) {
            $this->adventureParagraphs[] = $adventureParagraph;
            $adventureParagraph->setParagraph($this);
        }

        return $this;
    }

    public function removeAdventureParagraph(AdventureParagraph $adventureParagraph): self
    {
        if ($this->adventureParagraphs->removeElement($adventureParagraph)) {
            // set the owning side to null (unless already changed)
            if ($adventureParagraph->getParagraph() === $this) {
                $adventureParagraph->setParagraph(null);
            }
        }

        return $this;
    }

}
