<?php

namespace App\Entity;

use App\Repository\MerchantInventoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'merchantinventory', targetEntity: AdventureMerchantInventory::class)]
    private $adventureMerchantInventories;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    public function __construct()
    {
        $this->adventureMerchantInventories = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, AdventureMerchantInventory>
     */
    public function getAdventureMerchantInventories(): Collection
    {
        return $this->adventureMerchantInventories;
    }

    public function addAdventureMerchantInventory(AdventureMerchantInventory $adventureMerchantInventory): self
    {
        if (!$this->adventureMerchantInventories->contains($adventureMerchantInventory)) {
            $this->adventureMerchantInventories[] = $adventureMerchantInventory;
            $adventureMerchantInventory->setMerchantinventory($this);
        }

        return $this;
    }

    public function removeAdventureMerchantInventory(AdventureMerchantInventory $adventureMerchantInventory): self
    {
        if ($this->adventureMerchantInventories->removeElement($adventureMerchantInventory)) {
            // set the owning side to null (unless already changed)
            if ($adventureMerchantInventory->getMerchantinventory() === $this) {
                $adventureMerchantInventory->setMerchantinventory(null);
            }
        }

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
