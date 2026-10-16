<?php

namespace App\Form;

use App\Entity\AdventureParagraph;
use App\Entity\Adventure;
use App\Entity\Paragraph;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class AdventureParagraphType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('adventure', EntityType::class,['class' => Adventure::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('g')->orderBy('g.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Adventure'])
        ->add('paragraph', EntityType::class,['class' => Paragraph::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('p')->orderBy('p.id', 'ASC');}, 'choice_label' => 'number', 'label' => 'Paragraph'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdventureParagraph::class,
        ]);
    }
}
