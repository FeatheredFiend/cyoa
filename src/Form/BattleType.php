<?php

namespace App\Form;

use App\Entity\Battle;
use App\Entity\AdventureParagraph;
use App\Entity\Enemy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class BattleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('round', TextType::class,['label'=>'Round'])
            ->add('playerstamina', TextType::class,['label'=>'Player Stamina'])
            ->add('playerskill', TextType::class,['label'=>'Player Skill'])
            ->add('enemystamina', TextType::class,['label'=>'Enemy Stamina'])
            ->add('enemyskill', TextType::class,['label'=>'Enemy Skill'])
            ->add('enemy', EntityType::class,['class' => Enemy::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('e')->orderBy('e.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Enemy'])
            ->add('adventureparagraph', EntityType::class,['class' => AdventureParagraph::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('ap')->orderBy('ap.id', 'ASC');}, 'choice_label' => 'paragraph', 'label' => 'Adventure Paragraph'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Battle::class,
        ]);
    }
}
