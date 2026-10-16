<?php

namespace App\Entity;

use App\Repository\GamebookPermissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamebookPermissionRepository::class)]
class GamebookPermission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Gamebook::class, inversedBy: 'gamebookPermissions')]
    #[ORM\JoinColumn(nullable: false)]
    private $gamebook;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'gamebookPermissions')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
