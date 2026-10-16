<?php

namespace App\Entity;

use App\Repository\ParagraphSpellCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphSpellCategoryRepository::class)]
class ParagraphSpellCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'paragraphspellcategory', targetEntity: ParagraphSpell::class)]
    private $paragraphSpells;

    public function __construct()
    {
        $this->paragraphSpells = new ArrayCollection();
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
     * @return Collection<int, ParagraphSpell>
     */
    public function getParagraphSpells(): Collection
    {
        return $this->paragraphSpells;
    }

    public function addParagraphSpell(ParagraphSpell $paragraphSpell): self
    {
        if (!$this->paragraphSpells->contains($paragraphSpell)) {
            $this->paragraphSpells[] = $paragraphSpell;
            $paragraphSpell->setParagraphspellcategory($this);
        }

        return $this;
    }

    public function removeParagraphSpell(ParagraphSpell $paragraphSpell): self
    {
        if ($this->paragraphSpells->removeElement($paragraphSpell)) {
            // set the owning side to null (unless already changed)
            if ($paragraphSpell->getParagraphspellcategory() === $this) {
                $paragraphSpell->setParagraphspellcategory(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
