<?php

namespace App\Entity;

use App\Repository\EnemyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnemyRepository::class)]
class Enemy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'enemies')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $skill;

    #[ORM\Column(type: 'integer')]
    private $stamina;

    #[ORM\OneToMany(mappedBy: 'enemy', targetEntity: Battle::class)]
    private $battles;

    #[ORM\ManyToOne(targetEntity: BattleCategory::class, inversedBy: 'enemies')]
    #[ORM\JoinColumn(nullable: false)]
    private $battlecategory;

    public function __construct()
    {
        $this->battles = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSkill(): ?int
    {
        return $this->skill;
    }

    public function setSkill(int $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getStamina(): ?int
    {
        return $this->stamina;
    }

    public function setStamina(int $stamina): self
    {
        $this->stamina = $stamina;

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
            $battle->setEnemy($this);
        }

        return $this;
    }

    public function removeBattle(Battle $battle): self
    {
        if ($this->battles->removeElement($battle)) {
            // set the owning side to null (unless already changed)
            if ($battle->getEnemy() === $this) {
                $battle->setEnemy(null);
            }
        }

        return $this;
    }

    public function getBattlecategory(): ?BattleCategory
    {
        return $this->battlecategory;
    }

    public function setBattlecategory(?BattleCategory $battlecategory): self
    {
        $this->battlecategory = $battlecategory;

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
