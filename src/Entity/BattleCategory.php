<?php

namespace App\Entity;

use App\Repository\BattleCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BattleCategoryRepository::class)]
class BattleCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'battlecategory', targetEntity: Enemy::class)]
    private $enemies;

    public function __construct()
    {
        $this->enemies = new ArrayCollection();
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
            $enemy->setBattlecategory($this);
        }

        return $this;
    }

    public function removeEnemy(Enemy $enemy): self
    {
        if ($this->enemies->removeElement($enemy)) {
            // set the owning side to null (unless already changed)
            if ($enemy->getBattlecategory() === $this) {
                $enemy->setBattlecategory(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
