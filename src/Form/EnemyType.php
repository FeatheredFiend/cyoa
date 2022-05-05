<?php

namespace App\Form;

use App\Entity\Enemy;
use App\Entity\Paragraph;
use App\Entity\BattleCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class EnemyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,['label'=>'Name'])
            ->add('skill', TextType::class,['label'=>'Skill'])
            ->add('stamina', TextType::class,['label'=>'Stamina'])
            ->add('paragraph', EntityType::class,['class' => Paragraph::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('ap')->orderBy('ap.id', 'ASC');}, 'choice_label' => 'number', 'label' => 'Paragraph'])
            ->add('battlecategory', EntityType::class,['class' => BattleCategory::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('ap')->orderBy('ap.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Battle Category'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enemy::class,
        ]);
    }
}
