<?php

namespace App\Entity;

use App\Repository\ParagraphActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'paragraphaction', targetEntity: ParagraphActionEquipmentRequired::class)]
    private $paragraphActionEquipmentRequireds;

    #[ORM\Column(type: 'integer')]
    private $numberdice;

    #[ORM\OneToMany(mappedBy: 'paragraphaction', targetEntity: ParagraphActionEnemy::class)]
    private $paragraphActionEnemies;

    #[ORM\OneToMany(mappedBy: 'paragraphaction', targetEntity: ParagraphActionSpell::class)]
    private $paragraphActionSpells;

    public function __construct()
    {
        $this->paragraphActionEquipmentRequireds = new ArrayCollection();
        $this->paragraphActionEnemies = new ArrayCollection();
        $this->paragraphActionSpells = new ArrayCollection();
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

    /**
     * @return Collection<int, ParagraphActionEquipmentRequired>
     */
    public function getParagraphActionEquipmentRequireds(): Collection
    {
        return $this->paragraphActionEquipmentRequireds;
    }

    public function addParagraphActionEquipmentRequired(ParagraphActionEquipmentRequired $paragraphActionEquipmentRequired): self
    {
        if (!$this->paragraphActionEquipmentRequireds->contains($paragraphActionEquipmentRequired)) {
            $this->paragraphActionEquipmentRequireds[] = $paragraphActionEquipmentRequired;
            $paragraphActionEquipmentRequired->setParagraphaction($this);
        }

        return $this;
    }

    public function removeParagraphActionEquipmentRequired(ParagraphActionEquipmentRequired $paragraphActionEquipmentRequired): self
    {
        if ($this->paragraphActionEquipmentRequireds->removeElement($paragraphActionEquipmentRequired)) {
            // set the owning side to null (unless already changed)
            if ($paragraphActionEquipmentRequired->getParagraphaction() === $this) {
                $paragraphActionEquipmentRequired->setParagraphaction(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->text;
    }

    public function getNumberdice(): ?int
    {
        return $this->numberdice;
    }

    public function setNumberdice(int $numberdice): self
    {
        $this->numberdice = $numberdice;

        return $this;
    }

    /**
     * @return Collection<int, ParagraphActionEnemy>
     */
    public function getParagraphActionEnemies(): Collection
    {
        return $this->paragraphActionEnemies;
    }

    public function addParagraphActionEnemy(ParagraphActionEnemy $paragraphActionEnemy): self
    {
        if (!$this->paragraphActionEnemies->contains($paragraphActionEnemy)) {
            $this->paragraphActionEnemies[] = $paragraphActionEnemy;
            $paragraphActionEnemy->setParagraphaction($this);
        }

        return $this;
    }

    public function removeParagraphActionEnemy(ParagraphActionEnemy $paragraphActionEnemy): self
    {
        if ($this->paragraphActionEnemies->removeElement($paragraphActionEnemy)) {
            // set the owning side to null (unless already changed)
            if ($paragraphActionEnemy->getParagraphaction() === $this) {
                $paragraphActionEnemy->setParagraphaction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ParagraphActionSpell>
     */
    public function getParagraphActionSpells(): Collection
    {
        return $this->paragraphActionSpells;
    }

    public function addParagraphActionSpell(ParagraphActionSpell $paragraphActionSpell): self
    {
        if (!$this->paragraphActionSpells->contains($paragraphActionSpell)) {
            $this->paragraphActionSpells[] = $paragraphActionSpell;
            $paragraphActionSpell->setParagraphaction($this);
        }

        return $this;
    }

    public function removeParagraphActionSpell(ParagraphActionSpell $paragraphActionSpell): self
    {
        if ($this->paragraphActionSpells->removeElement($paragraphActionSpell)) {
            // set the owning side to null (unless already changed)
            if ($paragraphActionSpell->getParagraphaction() === $this) {
                $paragraphActionSpell->setParagraphaction(null);
            }
        }

        return $this;
    }
}
