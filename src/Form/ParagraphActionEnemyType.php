<?php

namespace App\Form;

use App\Entity\ParagraphActionEnemy;
use App\Entity\ParagraphAction;
use App\Entity\Enemy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ParagraphActionEnemyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paragraphaction', EntityType::class,['class' => ParagraphAction::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('pa')->orderBy('pa.id', 'ASC');}, 'choice_label' => 'text', 'label' => 'Paragraph Action'])
            ->add('enemy', EntityType::class,['class' => Enemy::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('e')->orderBy('e.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Enemy'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParagraphActionEnemy::class,
        ]);
    }
}
