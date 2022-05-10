<?php

namespace App\Form;

use App\Entity\Hero;
use App\Entity\Adventure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class HeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,['label'=>'Name'])
            ->add('skill', TextType::class,['label'=>'Skill'])
            ->add('stamina', TextType::class,['label'=>'Stamina'])
            ->add('luck', TextType::class,['label'=>'Luck'])
            ->add('honour', TextType::class,['label'=>'Honour'])
            ->add('startingskill', TextType::class,['label'=>'Starting Skill'])
            ->add('startingstamina', TextType::class,['label'=>'Starting Stamina'])
            ->add('startingluck', TextType::class,['label'=>'Starting Luck'])
            ->add('startingprovision', TextType::class,['label'=>'Starting Provisions'])
            ->add('provision', TextType::class,['label'=>'Provisions'])
            ->add('treasure', TextType::class,['label'=>'Treasure'])
            ->add('adventure', EntityType::class,['class' => Adventure::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('a')->orderBy('a.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Adventure'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hero::class,
        ]);
    }
}
