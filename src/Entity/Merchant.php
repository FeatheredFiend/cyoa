<?php

namespace App\Entity;

use App\Repository\MerchantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MerchantRepository::class)]
class Merchant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 25)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'merchants')]
    #[ORM\JoinColumn(nullable: false)]
    private $paragraph;

    #[ORM\OneToMany(mappedBy: 'merchant', targetEntity: MerchantInventory::class)]
    private $merchantInventories;

    #[ORM\OneToMany(mappedBy: 'merchant', targetEntity: AdventureMerchantInventory::class)]
    private $adventureMerchantInventories;

    public function __construct()
    {
        $this->merchantInventories = new ArrayCollection();
        $this->adventureMerchantInventories = new ArrayCollection();
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

    public function getParagraph(): ?Paragraph
    {
        return $this->paragraph;
    }

    public function setParagraph(?Paragraph $paragraph): self
    {
        $this->paragraph = $paragraph;

        return $this;
    }

    /**
     * @return Collection<int, MerchantInventory>
     */
    public function getMerchantInventories(): Collection
    {
        return $this->merchantInventories;
    }

    public function addMerchantInventory(MerchantInventory $merchantInventory): self
    {
        if (!$this->merchantInventories->contains($merchantInventory)) {
            $this->merchantInventories[] = $merchantInventory;
            $merchantInventory->setMerchant($this);
        }

        return $this;
    }

    public function removeMerchantInventory(MerchantInventory $merchantInventory): self
    {
        if ($this->merchantInventories->removeElement($merchantInventory)) {
            // set the owning side to null (unless already changed)
            if ($merchantInventory->getMerchant() === $this) {
                $merchantInventory->setMerchant(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
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
            $adventureMerchantInventory->setMerchant($this);
        }

        return $this;
    }

    public function removeAdventureMerchantInventory(AdventureMerchantInventory $adventureMerchantInventory): self
    {
        if ($this->adventureMerchantInventories->removeElement($adventureMerchantInventory)) {
            // set the owning side to null (unless already changed)
            if ($adventureMerchantInventory->getMerchant() === $this) {
                $adventureMerchantInventory->setMerchant(null);
            }
        }

        return $this;
    }

}
