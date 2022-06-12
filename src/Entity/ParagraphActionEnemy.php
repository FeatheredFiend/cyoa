<?php

namespace App\Entity;

use App\Repository\ParagraphActionEnemyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphActionEnemyRepository::class)]
class ParagraphActionEnemy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ParagraphAction::class, inversedBy: 'paragraphActionEnemies')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraphaction;

    #[ORM\ManyToOne(targetEntity: Enemy::class, inversedBy: 'paragraphActionEnemies')]
    #[ORM\JoinColumn(nullable: false)]
    private $enemy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParagraphaction(): ?ParagraphAction
    {
        return $this->paragraphaction;
    }

    public function setParagraphaction(?ParagraphAction $paragraphaction): self
    {
        $this->paragraphaction = $paragraphaction;

        return $this;
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
}
