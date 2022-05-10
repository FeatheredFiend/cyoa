<?php

namespace App\Entity;

use App\Repository\MerchantInventoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MerchantInventoryRepository::class)]
class MerchantInventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'merchantInventories')]
    #[ORM\JoinColumn(nullable: false)]
    private $merchant;

    #[ORM\ManyToOne(targetEntity: Equipment::class, inversedBy: 'merchantInventories')]
    #[ORM\JoinColumn(nullable: false)]
    private $equipment;

    #[ORM\Column(type: 'integer')]
    private $cost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(?Merchant $merchant): self
    {
        $this->merchant = $merchant;

        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }
}
