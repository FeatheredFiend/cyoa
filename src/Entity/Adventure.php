<?php

namespace App\Entity;

use App\Repository\AdventureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdventureRepository::class)]
class Adventure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Gamebook::class, inversedBy: 'adventures')]
    #[ORM\JoinColumn(nullable: false)]
    private $gamebook;

    #[ORM\OneToOne(inversedBy: 'adventure', targetEntity: Hero::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $hero;

    #[ORM\Column(type: 'integer')]
    private $timeelapsed;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'adventures')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'adventure', targetEntity: AdventureParagraph::class)]
    private $adventureParagraphs;

    #[ORM\Column(type: 'integer')]
    private $progressparagraph;

    #[ORM\OneToMany(mappedBy: 'adventure', targetEntity: AdventureMerchantInventory::class)]
    private $adventureMerchantInventories;

    public function __construct()
    {
        $this->adventureParagraphs = new ArrayCollection();
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

    public function getGamebook(): ?Gamebook
    {
        return $this->gamebook;
    }

    public function setGamebook(?Gamebook $gamebook): self
    {
        $this->gamebook = $gamebook;

        return $this;
    }

    public function getHero(): ?Hero
    {
        return $this->hero;
    }

    public function setHero(Hero $hero): self
    {
        $this->hero = $hero;

        return $this;
    }

    public function getTimeelapsed(): ?int
    {
        return $this->timeelapsed;
    }

    public function setTimeelapsed(int $timeelapsed): self
    {
        $this->timeelapsed = $timeelapsed;

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

    /**
     * @return Collection<int, AdventureParagraph>
     */
    public function getAdventureParagraphs(): Collection
    {
        return $this->adventureParagraphs;
    }

    public function addAdventureParagraph(AdventureParagraph $adventureParagraph): self
    {
        if (!$this->adventureParagraphs->contains($adventureParagraph)) {
            $this->adventureParagraphs[] = $adventureParagraph;
            $adventureParagraph->setAdventure($this);
        }

        return $this;
    }

    public function removeAdventureParagraph(AdventureParagraph $adventureParagraph): self
    {
        if ($this->adventureParagraphs->removeElement($adventureParagraph)) {
            // set the owning side to null (unless already changed)
            if ($adventureParagraph->getAdventure() === $this) {
                $adventureParagraph->setAdventure(null);
            }
        }

        return $this;
    }
    
    public function __toString() {
        return $this->name;
    }

    public function getProgressparagraph(): ?int
    {
        return $this->progressparagraph;
    }

    public function setProgressparagraph(int $progressparagraph): self
    {
        $this->progressparagraph = $progressparagraph;

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
            $adventureMerchantInventory->setAdventure($this);
        }

        return $this;
    }

    public function removeAdventureMerchantInventory(AdventureMerchantInventory $adventureMerchantInventory): self
    {
        if ($this->adventureMerchantInventories->removeElement($adventureMerchantInventory)) {
            // set the owning side to null (unless already changed)
            if ($adventureMerchantInventory->getAdventure() === $this) {
                $adventureMerchantInventory->setAdventure(null);
            }
        }

        return $this;
    }

}
