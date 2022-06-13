<?php

namespace App\Entity;

use App\Repository\ParagraphEquipmentCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParagraphEquipmentCategoryRepository::class)]
class ParagraphEquipmentCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'paragraphequipmentcategory', targetEntity: ParagraphEquipment::class)]
    private $paragraphEquipment;

    public function __construct()
    {
        $this->paragraphEquipment = new ArrayCollection();
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
     * @return Collection<int, ParagraphEquipment>
     */
    public function getParagraphEquipment(): Collection
    {
        return $this->paragraphEquipment;
    }

    public function addParagraphEquipment(ParagraphEquipment $paragraphEquipment): self
    {
        if (!$this->paragraphEquipment->contains($paragraphEquipment)) {
            $this->paragraphEquipment[] = $paragraphEquipment;
            $paragraphEquipment->setParagraphequipmentcategory($this);
        }

        return $this;
    }

    public function removeParagraphEquipment(ParagraphEquipment $paragraphEquipment): self
    {
        if ($this->paragraphEquipment->removeElement($paragraphEquipment)) {
            // set the owning side to null (unless already changed)
            if ($paragraphEquipment->getParagraphequipmentcategory() === $this) {
                $paragraphEquipment->setParagraphequipmentcategory(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
