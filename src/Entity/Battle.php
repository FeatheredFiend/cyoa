<?php

namespace App\Entity;

use App\Repository\BattleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BattleRepository::class)]
class Battle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Enemy::class, inversedBy: 'battles')]
    #[ORM\JoinColumn(nullable: false)]
    private $enemy;

    #[ORM\Column(type: 'integer')]
    private $round;

    #[ORM\Column(type: 'integer')]
    private $playerstamina;

    #[ORM\Column(type: 'integer')]
    private $playerskill;

    #[ORM\Column(type: 'integer')]
    private $enemystamina;

    #[ORM\Column(type: 'integer')]
    private $enemyskill;

    #[ORM\ManyToOne(targetEntity: AdventureParagraph::class, inversedBy: 'battles')]
    #[ORM\JoinColumn(nullable: false)]
    private $adventureparagraph;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnemy(): ?Enemy
    {
        return $this->enemy;
    }

    public function setEnemy(?Enemy $enemy): self
    {
        $this->enemy = $enemy;

        return $this;
    }

    public function getRound(): ?int
    {
        return $this->round;
    }

    public function setRound(int $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function getPlayerstamina(): ?int
    {
        return $this->playerstamina;
    }

    public function setPlayerstamina(int $playerstamina): self
    {
        $this->playerstamina = $playerstamina;

        return $this;
    }

    public function getPlayerskill(): ?int
    {
        return $this->playerskill;
    }

    public function setPlayerskill(int $playerskill): self
    {
        $this->playerskill = $playerskill;

        return $this;
    }

    public function getEnemystamina(): ?int
    {
        return $this->enemystamina;
    }

    public function setEnemystamina(int $enemystamina): self
    {
        $this->enemystamina = $enemystamina;

        return $this;
    }

    public function getEnemyskill(): ?int
    {
        return $this->enemyskill;
    }

    public function setEnemyskill(int $enemyskill): self
    {
        $this->enemyskill = $enemyskill;

        return $this;
    }

    public function getAdventureparagraph(): ?AdventureParagraph
    {
        return $this->adventureparagraph;
    }

    public function setAdventureparagraph(?AdventureParagraph $adventureparagraph): self
    {
        $this->adventureparagraph = $adventureparagraph;

        return $this;
    }
}
