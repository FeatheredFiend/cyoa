<?php

namespace App\Entity;

use App\Repository\AdventureMerchantInventoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdventureMerchantInventoryRepository::class)]
class AdventureMerchantInventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: MerchantInventory::class, inversedBy: 'adventureMerchantInventories')]
    #[ORM\JoinColumn(nullable: false)]
    private $merchantinventory;

    #[ORM\ManyToOne(targetEntity: Adventure::class, inversedBy: 'adventureMerchantInventories')]
    #[ORM\JoinColumn(nullable: false)]
    private $adventure;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'adventureMerchantInventories')]
    #[ORM\JoinColumn(nullable: false)]
    private $merchant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMerchantinventory(): ?MerchantInventory
    {
        return $this->merchantinventory;
    }

    public function setMerchantinventory(?MerchantInventory $merchantinventory): self
    {
        $this->merchantinventory = $merchantinventory;

        return $this;
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

    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(?Merchant $merchant): self
    {
        $this->merchant = $merchant;

        return $this;
    }
}
