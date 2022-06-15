<?php

namespace App\Entity;

use App\Repository\MagicEffectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagicEffectRepository::class)]
class MagicEffect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Magic::class, inversedBy: 'magicEffects')]
    #[ORM\JoinColumn(nullable: false)]
    private $magic;

    #[ORM\ManyToOne(targetEntity: MagicEffectOperator::class, inversedBy: 'magicEffects')]
    #[ORM\JoinColumn(nullable: false)]
    private $magiceffectoperator;

    #[ORM\ManyToOne(targetEntity: MagicEffectAttribute::class, inversedBy: 'magicEffects')]
    #[ORM\JoinColumn(nullable: false)]
    private $magiceffectattribute;

    #[ORM\Column(type: 'integer')]
    private $magiceffectvalue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMagic(): ?Magic
    {
        return $this->magic;
    }

    public function setMagic(?Magic $magic): self
    {
        $this->magic = $magic;

        return $this;
    }

    public function getMagiceffectoperator(): ?MagicEffectOperator
    {
        return $this->magiceffectoperator;
    }

    public function setMagiceffectoperator(?MagicEffectOperator $magiceffectoperator): self
    {
        $this->magiceffectoperator = $magiceffectoperator;

        return $this;
    }

    public function getMagiceffectattribute(): ?MagicEffectAttribute
    {
        return $this->magiceffectattribute;
    }

    public function setMagiceffectattribute(?MagicEffectAttribute $magiceffectattribute): self
    {
        $this->magiceffectattribute = $magiceffectattribute;

        return $this;
    }

    public function getMagiceffectvalue(): ?int
    {
        return $this->magiceffectvalue;
    }

    public function setMagiceffectvalue(int $magiceffectvalue): self
    {
        $this->magiceffectvalue = $magiceffectvalue;

        return $this;
    }
}
