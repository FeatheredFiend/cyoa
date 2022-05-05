<?php

namespace App\Form;

use App\Entity\Adventure;
use App\Entity\Gamebook;
use App\Entity\Hero;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AdventureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('timeelapsed', TextType::class,['label'=>'Time Elapsed'])
            ->add('gamebook', EntityType::class,['class' => Gamebook::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('g')->orderBy('g.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Gamebook'])
            ->add('hero', EntityType::class,['class' => Hero::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('h')->orderBy('h.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Hero'])
            ->add('user', EntityType::class,['class' => User::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'User'])
            ->add('name', TextType::class,['label'=>'Name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adventure::class,
        ]);
    }
}
