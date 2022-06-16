<?php

namespace App\Form;

use App\Entity\HeroSpell;
use App\Entity\Hero;
use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class HeroSpellType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hero', EntityType::class,['class' => Hero::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('h')->orderBy('h.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Hero'])
            ->add('spell', EntityType::class,['class' => Spell::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('s')->orderBy('s.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Spell'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HeroSpell::class,
        ]);
    }
}
