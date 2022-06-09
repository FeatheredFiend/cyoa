<?php

namespace App\Form;

use App\Entity\GamebookPermission;
use App\Entity\Gamebook;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GamebookPermissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gamebook', EntityType::class,['class' => Gamebook::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('g')->orderBy('g.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Gamebook'])
            ->add('user', EntityType::class,['class' => User::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'User'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GamebookPermission::class,
        ]);
    }
}
