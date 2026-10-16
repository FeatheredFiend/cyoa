<?php

namespace App\Entity;

use App\Repository\HeroAttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroAttributeRepository::class)]
class HeroAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'paragraphactionattribute', targetEntity: ParagraphAction::class)]
    private $paragraphActions;

    public function __construct()
    {
        $this->paragraphActions = new ArrayCollection();
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
     * @return Collection<int, ParagraphAction>
     */
    public function getParagraphActions(): Collection
    {
        return $this->paragraphActions;
    }

    public function addParagraphAction(ParagraphAction $paragraphAction): self
    {
        if (!$this->paragraphActions->contains($paragraphAction)) {
            $this->paragraphActions[] = $paragraphAction;
            $paragraphAction->setParagraphactionattribute($this);
        }

        return $this;
    }

    public function removeParagraphAction(ParagraphAction $paragraphAction): self
    {
        if ($this->paragraphActions->removeElement($paragraphAction)) {
            // set the owning side to null (unless already changed)
            if ($paragraphAction->getParagraphactionattribute() === $this) {
                $paragraphAction->setParagraphactionattribute(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
